package com.aplikasi.apk_spp;

import android.content.Context;
import android.os.Bundle;

import androidx.fragment.app.Fragment;

import android.text.Editable;
import android.text.TextWatcher;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.EditText;
import android.widget.ListView;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
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

/**
 * A simple {@link Fragment} subclass.
 * Use the {@link PembayaranFragment#newInstance} factory method to
 * create an instance of this fragment.
 */
public class PembayaranFragment extends Fragment {

    // TODO: Rename parameter arguments, choose names that match
    // the fragment initialization parameters, e.g. ARG_ITEM_NUMBER
    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";

    // TODO: Rename and change types of parameters
    private String mParam1;
    private String mParam2;

    public PembayaranFragment() {
        // Required empty public constructor
    }

    /**
     * Use this factory method to create a new instance of
     * this fragment using the provided parameters.
     *
     * @param param1 Parameter 1.
     * @param param2 Parameter 2.
     * @return A new instance of fragment PembayaranFragment.
     */
    // TODO: Rename and change types and number of parameters
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

    EditText etSeacrh;
    ListView listView;
    List<Siswa> siswaList;
    SiswaAdapter adapter;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment_pembayaran, container, false);

        etSeacrh = view.findViewById(R.id.etSearch);
        listView = view.findViewById(R.id.listView);
        siswaList = new ArrayList<>();

        loadData(getContext());

        etSeacrh.addTextChangedListener(new TextWatcher() {
            @Override
            public void afterTextChanged(Editable editable) {

            }

            @Override
            public void beforeTextChanged(CharSequence charSequence, int i, int i1, int i2) {

            }

            @Override
            public void onTextChanged(CharSequence charSequence, int i, int i1, int i2) {
                siswaList.clear();
                searchData(getContext(), charSequence.toString());
            }
        });

        return view;
    }

    private void loadData(Context context){
        siswaList.clear();
        StringRequest request = new StringRequest(Request.Method.GET, Helper.URLGetSiswa, new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                try {
                    JSONObject object = new JSONObject(response);
                    String data = object.getString("siswa");
                    JSONArray siswa = new JSONArray(data);
                    for (int i = 0; i < siswa.length(); i++) {
                        JSONObject jsonObject = siswa.getJSONObject(i);
                        String nisn = jsonObject.getString("nisn");
                        String nis = jsonObject.getString("nis");
                        String nama = jsonObject.getString("nama");
                        String nama_kelas = jsonObject.getString("nama_kelas");
                        String kompetensi_keahlian = jsonObject.getString("kompetensi_keahlian");
                        Double nominal = jsonObject.getDouble("nominal");
                        String status_pembayaran = jsonObject.getString("status_pembayaran");

                        siswaList.add(new Siswa(nisn, nis, nama, nama_kelas, kompetensi_keahlian, nominal, status_pembayaran));
                    }
                    adapter = new SiswaAdapter(context, siswaList);
                    listView.setAdapter(adapter);
                } catch (JSONException e) {
                    e.printStackTrace();
                    Helper.Alert("Error JSON", e.getMessage(), context);
                }
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                if (error.networkResponse != null && error.networkResponse.data != null) {
                    if(error.networkResponse.statusCode == 401){
                        Helper.Alert("Error", "Token Kedaluwarsa, Silahkan Login Kembali", context);
                        return;
                    }
                    Helper.Alert("Error", "Terjadi kesalahan saat membaca respon server.", context);
                } else {
                    Helper.Alert("Error", "Tidak ada koneksi internet atau server tidak merespon.", context);
                }
            }
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

    private void searchData(Context context, String keyword){
        siswaList.clear();
        StringRequest request = new StringRequest(Request.Method.GET, Helper.URLGetSiswa + keyword, new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                try {
                    JSONObject object = new JSONObject(response);
                    String data = object.getString("siswa");
                    JSONArray siswa = new JSONArray(data);
                    for (int i = 0; i < siswa.length(); i++) {
                        JSONObject jsonObject = siswa.getJSONObject(i);
                        String nisn = jsonObject.getString("nisn");
                        String nis = jsonObject.getString("nis");
                        String nama = jsonObject.getString("nama");
                        String nama_kelas = jsonObject.getString("nama_kelas");
                        String kompetensi_keahlian = jsonObject.getString("kompetensi_keahlian");
                        Double nominal = jsonObject.getDouble("nominal");
                        String status_pembayaran = jsonObject.getString("status_pembayaran");

                        siswaList.add(new Siswa(nisn, nis, nama, nama_kelas, kompetensi_keahlian, nominal, status_pembayaran));
                    }
                    adapter = new SiswaAdapter(context, siswaList);
                    listView.setAdapter(adapter);
                } catch (JSONException e) {
                    e.printStackTrace();
                    Helper.Alert("Error JSON", e.getMessage(), context);
                }
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                if (error.networkResponse != null && error.networkResponse.data != null) {
                    if(error.networkResponse.statusCode == 401){
                        Helper.Alert("Error", "Token Kedaluwarsa, Silahkan Login Kembali", context);
                        return;
                    }
                    Helper.Alert("Error", "Terjadi kesalahan saat membaca respon server.", context);
                } else {
                    Helper.Alert("Error", "Tidak ada koneksi internet atau server tidak merespon.", context);
                }
            }
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