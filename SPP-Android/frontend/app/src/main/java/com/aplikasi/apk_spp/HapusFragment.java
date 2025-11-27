package com.aplikasi.apk_spp;

import android.content.Context;
import android.os.Bundle;

import androidx.appcompat.app.AlertDialog;
import androidx.fragment.app.Fragment;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.ListView;

import com.android.volley.Request;
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
 * Use the {@link HapusFragment#newInstance} factory method to
 * create an instance of this fragment.
 */
public class HapusFragment extends Fragment {

    // TODO: Rename parameter arguments, choose names that match
    // the fragment initialization parameters, e.g. ARG_ITEM_NUMBER
    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";

    // TODO: Rename and change types of parameters
    private String mParam1;
    private String mParam2;

    public HapusFragment() {
        // Required empty public constructor
    }

    /**
     * Use this factory method to create a new instance of
     * this fragment using the provided parameters.
     *
     * @param param1 Parameter 1.
     * @param param2 Parameter 2.
     * @return A new instance of fragment HapusFragment.
     */
    // TODO: Rename and change types and number of parameters
    public static HapusFragment newInstance(String param1, String param2) {
        HapusFragment fragment = new HapusFragment();
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

    List<Hapus> hapusList;
    HapusAdapter adapter;
    ListView listView;

    ImageView ivLogout, ivBack;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment_hapus, container, false);

        hapusList = new ArrayList<>();
        listView = view.findViewById(R.id.listView);
        ivLogout = view.findViewById(R.id.ivLogout);
        ivBack = view.findViewById(R.id.ivBack);

        ivBack.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                getActivity().getSupportFragmentManager().beginTransaction().replace(R.id.frame_container, new PembayaranFragment()).commit();
            }
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

        loadData(getContext());

        return view;
    }

    private void loadData(Context context){
        StringRequest request = new StringRequest(Request.Method.GET, Helper.URLGetHapus, new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                try {
                    JSONObject jsonObject = new JSONObject(response);
                    String data = jsonObject.getString("hapusData");
                    JSONArray jsonArray = new JSONArray(data);
                    for (int i = 0; i < jsonArray.length(); i++) {
                        JSONObject hapus = jsonArray.getJSONObject(i);
                        String nis = hapus.getString("nis");
                        String nama = hapus.getString("nama");
                        String kelas = hapus.getString("kelas");
                        Double nominal = hapus.getDouble("nominal");
                        String bulan = hapus.getString("bulan");
                        String tgl_bayar = hapus.getString("tgl_bayar");

                        hapusList.add(new Hapus(nis, nama, kelas, nominal, bulan, tgl_bayar));
                    }
                    adapter = new HapusAdapter(context, hapusList);
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

        Volley.newRequestQueue(context).add(request);
    }
}