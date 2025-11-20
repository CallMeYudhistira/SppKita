package com.aplikasi.apk_spp;

import android.content.Context;
import android.content.DialogInterface;
import android.os.Bundle;

import androidx.appcompat.app.AlertDialog;
import androidx.fragment.app.Fragment;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.TextView;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.text.NumberFormat;
import java.util.ArrayList;
import java.util.Collections;
import java.util.HashMap;
import java.util.List;
import java.util.Locale;
import java.util.Map;

public class BayarFragment extends Fragment {

    String nisn, nis, nama, kelas;
    Double nominal, total;

    TextView tvNis, tvNama, tvKelas, pilihBulan, tvTotal;
    Button btnBayar, btnKembali;

    List<String> bulanList;
    List<Integer> indexBulan;
    boolean[] bulanDipilih;

    public BayarFragment() {}

    public static BayarFragment newInstance(String param1, String param2) {
        BayarFragment fragment = new BayarFragment();
        Bundle args = new Bundle();
        fragment.setArguments(args);
        return fragment;
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {

        View view = inflater.inflate(R.layout.fragment_bayar, container, false);

        bulanList = new ArrayList<>();
        indexBulan = new ArrayList<>();

        tvNis = view.findViewById(R.id.tvNis);
        tvNama = view.findViewById(R.id.tvNama);
        tvKelas = view.findViewById(R.id.tvKelas);
        pilihBulan = view.findViewById(R.id.pilihBulan);
        tvTotal = view.findViewById(R.id.tvTotal);
        btnBayar = view.findViewById(R.id.btnBayar);
        btnKembali = view.findViewById(R.id.btnKembali);
        NumberFormat format = NumberFormat.getCurrencyInstance(new Locale("ID", "id"));

        if (getArguments() != null) {
            nisn = getArguments().getString("nisn", "");
            nis = getArguments().getString("nis", "");
            nama = getArguments().getString("nama", "");
            kelas = getArguments().getString("kelas", "");
            nominal = getArguments().getDouble("nominal", 0);

            tvNis.setText(nis);
            tvNama.setText(nama);
            tvKelas.setText(kelas);
            tvTotal.setText(format.format(nominal * indexBulan.size()));
        }

        loadBulan(getContext());

        btnKembali.setOnClickListener(v -> {
            DetailFragment detailFragment = new DetailFragment();
            Bundle bundle = new Bundle();
            bundle.putString("nisn", nisn);
            detailFragment.setArguments(bundle);

            getActivity().getSupportFragmentManager()
                    .beginTransaction()
                    .replace(R.id.frame_container, detailFragment)
                    .commit();
        });

        pilihBulan.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                if (bulanList == null || bulanList.size() == 0 || bulanDipilih == null) {
                    Helper.Alert("Peringatan", "Data bulan belum dimuat!", getContext());
                    return;
                }

                AlertDialog.Builder builder = new AlertDialog.Builder(getContext());
                builder.setTitle("Pilih Bulan Yang Akan Dibayar");
                builder.setCancelable(false);

                builder.setMultiChoiceItems(bulanList.toArray(new String[0]), bulanDipilih,
                        new DialogInterface.OnMultiChoiceClickListener() {
                            @Override
                            public void onClick(DialogInterface dialog, int which, boolean isChecked) {
                                if (isChecked) {
                                    indexBulan.add(which);
                                    Collections.sort(indexBulan);
                                } else {
                                    indexBulan.remove(Integer.valueOf(which));
                                }
                            }
                        });

                builder.setPositiveButton("OK", (dialog, which) -> {
                    StringBuilder stringBuilder = new StringBuilder();

                    for (int j = 0; j < indexBulan.size(); j++) {
                        stringBuilder.append(bulanList.get(indexBulan.get(j)));

                        if (j != indexBulan.size() - 1) {
                            stringBuilder.append(", ");
                        }
                    }

                    total = nominal * indexBulan.size();
                    pilihBulan.setText(stringBuilder.toString());
                    tvTotal.setText(format.format(nominal * indexBulan.size()));
                });

                builder.setNeutralButton("Bersihkan", (dialog, which) -> {
                    for (int j = 0; j < bulanDipilih.length; j++) {
                        bulanDipilih[j] = false;
                    }
                    indexBulan.clear();
                    pilihBulan.setText("");
                    tvTotal.setText(format.format(nominal * indexBulan.size()));
                    total = 0.0;
                });

                builder.show();
            }
        });

        return view;
    }

    private void loadBulan(Context context) {
        StringRequest request = new StringRequest(Request.Method.GET, Helper.URLGetBulan + nisn,
                response -> {
                    try {
                        JSONObject jsonObject = new JSONObject(response);
                        JSONArray bulan = jsonObject.getJSONArray("bulan");

                        bulanList = new ArrayList<>();
                        for (int i = 0; i < bulan.length(); i++) {
                            bulanList.add(bulan.getString(i));
                        }

                        bulanDipilih = new boolean[bulanList.size()];
                        for (int i = 0; i < bulanDipilih.length; i++) {
                            bulanDipilih[i] = false;
                        }

                    } catch (JSONException e) {
                        Helper.Alert("Error JSON", e.getMessage(), context);
                    }
                },
                error -> {
                    if (error.networkResponse != null && error.networkResponse.data != null) {
                        if (error.networkResponse.statusCode == 401) {
                            Helper.Alert("Error", "Token kedaluwarsa, silakan login kembali", context);
                            return;
                        }
                        Helper.Alert("Error", "Terjadi kesalahan saat membaca respon server.", context);
                    } else {
                        Helper.Alert("Error", "Tidak ada koneksi internet atau server tidak merespon.", context);
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
