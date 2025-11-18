package com.aplikasi.apk_spp;

import android.content.Context;
import android.os.Bundle;

import androidx.fragment.app.Fragment;

import android.text.Editable;
import android.text.TextWatcher;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.EditText;
import android.widget.ListView;
import android.widget.Spinner;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

public class PembayaranFragment extends Fragment {

    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";

    private String mParam1;
    private String mParam2;

    public PembayaranFragment() { }

    public static PembayaranFragment newInstance(String param1, String param2) {
        PembayaranFragment fragment = new PembayaranFragment();
        Bundle args = new Bundle();
        args.putString(ARG_PARAM1, param1);
        args.putString(ARG_PARAM2, param2);
        fragment.setArguments(args);
        return fragment;
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
            mParam1 = getArguments().getString(ARG_PARAM1);
            mParam2 = getArguments().getString(ARG_PARAM2);
        }
    }

    // UI
    EditText etSearch;
    ListView listView;
    Spinner dropdown_kelas;

    // Data
    List<Siswa> siswaList;
    SiswaAdapter adapter;

    ArrayList<String> kelasList, idKelasList;
    String idKelas = "";
    String namaSiswa = "";

    // FLAG AGAR SPINNER TIDAK LOOPING
    boolean isSpinnerInitialized = false;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment_pembayaran, container, false);

        etSearch = view.findViewById(R.id.etSearch);
        listView = view.findViewById(R.id.listView);
        dropdown_kelas = view.findViewById(R.id.dropdown_kelas);

        siswaList = new ArrayList<>();

        // Search nama siswa real-time
        etSearch.addTextChangedListener(new TextWatcher() {
            @Override public void afterTextChanged(Editable editable) { }
            @Override public void beforeTextChanged(CharSequence charSequence, int i, int i1, int i2) { }

            @Override
            public void onTextChanged(CharSequence charSequence, int i, int i1, int i2) {
                namaSiswa = charSequence.toString();
                loadData(getContext(), "?nama=" + namaSiswa + "&kelas=" + idKelas);
            }
        });

        // Load awal
        loadData(getContext(), "?nama=" + namaSiswa + "&kelas=" + idKelas);

        // Adapter spinner
        ArrayAdapter<String> kelasAdapter =
                new ArrayAdapter<>(getContext(),
                        android.R.layout.simple_spinner_dropdown_item,
                        kelasList);

        dropdown_kelas.setAdapter(kelasAdapter);


        // ============================
        // ANTI LOOPING SPINNER
        // ============================
        if (!isSpinnerInitialized) {
            isSpinnerInitialized = true;

            dropdown_kelas.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
                @Override
                public void onItemSelected(AdapterView<?> adapterView, View view, int position, long l) {

                    if (position >= idKelasList.size()) return;

                    idKelas = idKelasList.get(position);

                    // Load ulang jika user memilih
                    loadData(getContext(), "?nama=" + namaSiswa + "&kelas=" + idKelas);
                }

                @Override
                public void onNothingSelected(AdapterView<?> adapterView) { }
            });
        }

        return view;
    }

    private void loadData(Context context, String search) {
        siswaList.clear();

        // Siapkan array kelas
        kelasList = new ArrayList<>();
        idKelasList = new ArrayList<>();

        // Default opsi "Semua Kelas"
        kelasList.add("Semua Kelas");
        idKelasList.add("");

        StringRequest request = new StringRequest(Request.Method.GET, Helper.URLGetSiswa + search,
                response -> {
                    try {
                        JSONObject object = new JSONObject(response);

                        // ============================
                        // Parsing Data Siswa
                        // ============================
                        String siswa = object.getString("siswa");
                        JSONArray siswaArray = new JSONArray(siswa);

                        for (int i = 0; i < siswaArray.length(); i++) {
                            JSONObject json = siswaArray.getJSONObject(i);

                            siswaList.add(new Siswa(
                                    json.getString("nisn"),
                                    json.getString("nis"),
                                    json.getString("nama"),
                                    json.getString("nama_kelas"),
                                    json.getString("kompetensi_keahlian"),
                                    json.getDouble("nominal"),
                                    json.getString("status_pembayaran")
                            ));
                        }

                        adapter = new SiswaAdapter(context, siswaList);
                        listView.setAdapter(adapter);

                        // ============================
                        // Parsing Data Kelas
                        // ============================
                        String kelas = object.getString("kelas");
                        JSONArray kelasArray = new JSONArray(kelas);

                        for (int i = 0; i < kelasArray.length(); i++) {
                            JSONObject json = kelasArray.getJSONObject(i);

                            String id = json.getString("id_kelas");
                            String nama = json.getString("nama_kelas");
                            String kompetensi = json.getString("kompetensi_keahlian");

                            kelasList.add(nama + " " + kompetensi);
                            idKelasList.add(id);
                        }

                    } catch (JSONException e) {
                        e.printStackTrace();
                        Helper.Alert("Error JSON", e.getMessage(), context);
                    }
                },
                error -> {

                    if (error.networkResponse != null && error.networkResponse.statusCode == 401) {
                        Helper.Alert("Error", "Token Kedaluwarsa, Silahkan Login Kembali", context);
                        return;
                    }

                    Helper.Alert("Error", "Tidak dapat memuat data", context);
                }) {

            @Override
            public Map<String, String> getHeaders() {
                Map<String, String> headers = new HashMap<>();
                headers.put("Accept", "application/json");
                headers.put("Content-Type", "application/json");
                headers.put("Authorization", "Bearer " + Helper.token);
                return headers;
            }
        };

        RequestQueue queue = Volley.newRequestQueue(context);
        queue.add(request);
    }
}
