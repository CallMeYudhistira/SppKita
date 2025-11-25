package com.aplikasi.apk_spp;

import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.os.Bundle;

import androidx.appcompat.app.AlertDialog;
import androidx.fragment.app.Fragment;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.ListView;
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
import java.util.HashMap;
import java.util.List;
import java.util.Locale;
import java.util.Map;

/**
 * A simple {@link Fragment} subclass.
 * Use the {@link DetailFragment#newInstance} factory method to
 * create an instance of this fragment.
 */
public class DetailFragment extends Fragment {

    // TODO: Rename parameter arguments, choose names that match
    // the fragment initialization parameters, e.g. ARG_ITEM_NUMBER
    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";

    // TODO: Rename and change types of parameters
    private String mParam1;
    private String mParam2;

    public DetailFragment() {
        // Required empty public constructor
    }

    /**
     * Use this factory method to create a new instance of
     * this fragment using the provided parameters.
     *
     * @param param1 Parameter 1.
     * @param param2 Parameter 2.
     * @return A new instance of fragment DetailFragment.
     */
    // TODO: Rename and change types and number of parameters
    public static DetailFragment newInstance(String param1, String param2) {
        DetailFragment fragment = new DetailFragment();
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

    String nisn = "", id_spp = "";
    ImageView ivLogout, ivBack;
    TextView tvNis, tvNisn, tvNama, tvKelas, tvNominal, tvBulanDibayar, tvTotalDibayar, tvTunggakan, tvToobar;
    Button btnCetakKartu, btnBayar;
    ListView listView;
    List<Detail> detailList;
    DetailAdapter adapter;
    Double nominal;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment_detail, container, false);

        detailList = new ArrayList<>();
        listView = view.findViewById(R.id.listView);

        tvNis = view.findViewById(R.id.tvNis);
        tvNisn = view.findViewById(R.id.tvNisn);
        tvNama = view.findViewById(R.id.tvNama);
        tvKelas = view.findViewById(R.id.tvKelas);
        tvNominal = view.findViewById(R.id.tvNominal);
        tvBulanDibayar = view.findViewById(R.id.tvBulanDibayar);
        tvTotalDibayar = view.findViewById(R.id.tvTotalDibayar);
        tvTunggakan = view.findViewById(R.id.tvTunggakan);
        btnCetakKartu = view.findViewById(R.id.btnCetakKartu);
        btnBayar = view.findViewById(R.id.btnBayar);
        ivBack = view.findViewById(R.id.ivBack);

        if(Helper.level.equals("siswa")){
            btnBayar.setVisibility(View.GONE);
            tvToobar = view.findViewById(R.id.tvToolbar);
            tvToobar.setText("Riwayat Pembayaran");
            ivBack.setVisibility(View.GONE);
        }

        if (getArguments() != null) {
            nisn = getArguments().getString("nisn", "");
        }

        loadSiswa(getContext());

        ivBack = view.findViewById(R.id.ivBack);
        ivBack.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                getActivity().getSupportFragmentManager().beginTransaction().replace(R.id.frame_container, new PembayaranFragment()).commit();
            }
        });

        ivLogout = view.findViewById(R.id.ivLogout);
        ivLogout.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                AlertDialog.Builder builder = new AlertDialog.Builder(getContext());
                builder.setTitle("Logout");
                builder.setMessage("Apakah anda ingin logout?");
                builder.setPositiveButton("Ya", new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialogInterface, int i) {
                        Helper helper = new Helper();
                        helper.logout(getContext());
                    }
                });
                builder.setNegativeButton("Tidak", new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialogInterface, int i) {
                        dialogInterface.dismiss();
                    }
                });
                builder.show();
            }
        });

        btnBayar.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                BayarFragment bayarFragment = new BayarFragment();
                Bundle bundle = new Bundle();
                bundle.putString("nisn", nisn);
                bundle.putString("nis", tvNis.getText().toString());
                bundle.putString("nama", tvNama.getText().toString());
                bundle.putString("kelas", tvKelas.getText().toString());
                bundle.putString("id_spp", id_spp);
                bundle.putDouble("nominal", nominal);
                bayarFragment.setArguments(bundle);

                getActivity().getSupportFragmentManager().beginTransaction().replace(R.id.frame_container, bayarFragment).commit();
            }
        });

        btnCetakKartu.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(getContext(), KartuActivity.class);
                intent.putExtra("nisn", nisn);
                getContext().startActivity(intent);
                getActivity().finish();
            }
        });

        return view;
    }

    private void loadSiswa(Context context) {
        StringRequest request = new StringRequest(Request.Method.GET, Helper.URLGetDetail + nisn, new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                try {
                    JSONObject jsonObject = new JSONObject(response);
                    NumberFormat format = NumberFormat.getCurrencyInstance(new Locale("ID", "id"));

                    String jsonString = jsonObject.getString("pembayaran");
                    JSONArray data = new JSONArray(jsonString);
                    JSONObject pembayaran = data.getJSONObject(0);
                    nominal = pembayaran.getDouble("nominal");
                    id_spp = pembayaran.getString("id_spp");

                    tvNis.setText(pembayaran.getString("nis"));
                    tvNisn.setText(pembayaran.getString("nisn"));
                    tvNama.setText(pembayaran.getString("nama"));
                    tvKelas.setText(pembayaran.getString("nama_kelas") + " " + pembayaran.getString("kompetensi_keahlian"));
                    tvNominal.setText(format.format(nominal) + " - " + pembayaran.getString("tahun"));
                    tvBulanDibayar.setText(pembayaran.getString("bulan_dibayar") + " dari 12 Bulan");
                    if (pembayaran.getDouble("total_bayar") == 0) {
                        tvTotalDibayar.setText("0");
                        tvTunggakan.setText("0");
                    } else {
                        tvTotalDibayar.setText(format.format(pembayaran.getDouble("total_bayar")));
                        tvTunggakan.setText(format.format(pembayaran.getDouble("nominal") * 12 - pembayaran.getDouble("total_bayar")));
                    }

                    data = jsonObject.getJSONArray("hasil");
                    for (int i = 0; i < data.length(); i++) {
                        JSONObject siswa = data.getJSONObject(i);
                        detailList.add(new Detail(
                                siswa.getString("id_pembayaran"),
                                siswa.getString("bulan_dibayar"),
                                siswa.getString("tgl_bayar"),
                                siswa.getString("nama_petugas"),
                                siswa.getString("pesan")
                        ));
                    }

                    adapter = new DetailAdapter(context, detailList);
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