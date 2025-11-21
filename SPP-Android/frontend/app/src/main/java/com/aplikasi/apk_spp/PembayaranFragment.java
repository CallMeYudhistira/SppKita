package com.aplikasi.apk_spp;

import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.os.Bundle;

import androidx.appcompat.app.AlertDialog;
import androidx.fragment.app.Fragment;

import android.text.Editable;
import android.text.TextWatcher;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.ListView;
import android.widget.Spinner;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
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

    public static PembayaranFragment newInstance(String param1, String param2)
    {
        PembayaranFragment fragment = new PembayaranFragment();
        Bundle args = new Bundle(); args.putString(ARG_PARAM1, param1);
        args.putString(ARG_PARAM2, param2); fragment.setArguments(args);
        return fragment;
    }

    @Override public void onCreate(Bundle savedInstanceState)
    {
        super.onCreate(savedInstanceState);
        if (getArguments() != null)
        {
            mParam1 = getArguments().getString(ARG_PARAM1);
            mParam2 = getArguments().getString(ARG_PARAM2);
        }
    }

    EditText etSearch;
    ListView listView;
    Spinner dropdown_kelas;

    List<Siswa> siswaList;
    SiswaAdapter adapter;

    ArrayList<String> kelasList = new ArrayList<>();
    ArrayList<String> idKelasList = new ArrayList<>();

    String idKelas = "";
    String namaSiswa = "";

    ImageView ivLogout;

    boolean kelasLoaded = false;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment_pembayaran, container, false);

        ivLogout = view.findViewById(R.id.ivLogout);
        etSearch = view.findViewById(R.id.etSearch);
        listView = view.findViewById(R.id.listView);
        dropdown_kelas = view.findViewById(R.id.dropdown_kelas);

        siswaList = new ArrayList<>();

        kelasList.add("Semua Kelas");
        idKelasList.add("");

        loadKelas(getContext());
        loadSiswa(getContext());

        etSearch.addTextChangedListener(new TextWatcher() {
            @Override public void beforeTextChanged(CharSequence s, int start, int count, int after) {}
            @Override public void afterTextChanged(Editable s) {}

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {
                namaSiswa = s.toString();
                loadSiswa(getContext());
            }
        });

        dropdown_kelas.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
                idKelas = idKelasList.get(position);
                loadSiswa(getContext());
            }

            @Override public void onNothingSelected(AdapterView<?> parent) {}
        });

        ivLogout.setOnClickListener(v -> {
            AlertDialog.Builder builder = new AlertDialog.Builder(getContext());
            builder.setTitle("Logout")
                    .setMessage("Apakah anda ingin logout?")
                    .setPositiveButton("Ya", (dialog, i) -> {
                        Helper helper = new Helper();
                        helper.logout(getContext());
                    })
                    .setNegativeButton("Tidak", (dialog, i) -> dialog.dismiss())
                    .show();
        });

        return view;
    }

    private void loadKelas(Context context) {

        if (kelasLoaded) return;

        StringRequest request = new StringRequest(Request.Method.GET, Helper.URLGetSiswa,
                response -> {
                    try {
                        JSONObject object = new JSONObject(response);

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

                        ArrayAdapter<String> kelasAdapter =
                                new ArrayAdapter<>(context,
                                        android.R.layout.simple_spinner_dropdown_item,
                                        kelasList);

                        dropdown_kelas.setAdapter(kelasAdapter);

                        kelasLoaded = true;

                    } catch (JSONException e) {
                        Helper.Alert("Error JSON", e.getMessage(), context);
                    }
                },
                error -> {
                    Helper.Alert("Error", "Gagal memuat kelas", context);
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

        Volley.newRequestQueue(context).add(request);
    }

    private void loadSiswa(Context context) {

        String search = "?nama=" + namaSiswa + "&kelas=" + idKelas;

        StringRequest request = new StringRequest(Request.Method.GET, Helper.URLGetSiswa + search,
                response -> {
                    try {
                        JSONObject object = new JSONObject(response);

                        String siswa = object.getString("siswa");
                        JSONArray siswaArray = new JSONArray(siswa);
                        siswaList = new ArrayList<>();

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

                        adapter = new SiswaAdapter(context, siswaList, nisn -> {
                            DetailFragment detail = new DetailFragment();
                            Bundle b = new Bundle();
                            b.putString("nisn", nisn);
                            detail.setArguments(b);

                            requireActivity().getSupportFragmentManager()
                                    .beginTransaction()
                                    .replace(R.id.frame_container, detail)
                                    .addToBackStack(null)
                                    .commit();
                        });

                        listView.setAdapter(adapter);

                    } catch (JSONException e) {
                        Helper.Alert("Error JSON", e.getMessage(), context);
                    }
                },
                error -> {
                    Helper.Alert("Error", "Tidak dapat memuat siswa", context);
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

        Volley.newRequestQueue(context).add(request);
    }
}
