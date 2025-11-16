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
import android.widget.GridLayout;
import android.widget.ImageView;
import android.widget.TextView;

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

import java.text.NumberFormat;
import java.util.HashMap;
import java.util.Locale;
import java.util.Map;

/**
 * A simple {@link Fragment} subclass.
 * Use the {@link HomeFragment#newInstance} factory method to
 * create an instance of this fragment.
 */
public class HomeFragment extends Fragment {

    // TODO: Rename parameter arguments, choose names that match
    // the fragment initialization parameters, e.g. ARG_ITEM_NUMBER
    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";

    // TODO: Rename and change types of parameters
    private String mParam1;
    private String mParam2;

    public HomeFragment() {
        // Required empty public constructor
    }

    /**
     * Use this factory method to create a new instance of
     * this fragment using the provided parameters.
     *
     * @param param1 Parameter 1.
     * @param param2 Parameter 2.
     * @return A new instance of fragment HomeFragment.
     */
    // TODO: Rename and change types and number of parameters
    public static HomeFragment newInstance(String param1, String param2) {
        HomeFragment fragment = new HomeFragment();
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

    ImageView ivLogout;
    TextView txtDashboardTitle, txtDashboardSubTitle, txtNominalSpp, txtTotalSudahBayar, txtTotalTunggakanSiswa, txtPembayaranHariIni, txtTotalTunggakan, txtTotalTransaksi, txtTotalSiswa;
    GridLayout gridPetugas, gridSiswa;


    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment_home, container, false);

        gridPetugas = view.findViewById(R.id.gridPetugas);
        txtNominalSpp = view.findViewById(R.id.txtNominalSpp);
        txtTotalSudahBayar = view.findViewById(R.id.txtTotalSudahBayar);
        txtTotalTunggakan = view.findViewById(R.id.txtTotalTunggakan);

        gridSiswa = view.findViewById(R.id.gridSiswa);
        txtPembayaranHariIni = view.findViewById(R.id.txtPembayaranHariIni);
        txtTotalTransaksi = view.findViewById(R.id.txtTotalTransaksi);
        txtTotalTunggakanSiswa = view.findViewById(R.id.txtTotalTunggakanSiswa);
        txtTotalSiswa = view.findViewById(R.id.txtTotalSiswa);

        loadHome(getContext());

        txtDashboardTitle = view.findViewById(R.id.txtDashboardTitle);
        txtDashboardSubTitle = view.findViewById(R.id.txtDashboardSubTitle);
        txtDashboardTitle.setText("Dashboard " + Helper.level);
        txtDashboardSubTitle.setText("Halo, " + Helper.nama);

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
                        helper.flush();

                        Intent intent = new Intent(getActivity(), MainActivity.class);
                        startActivity(intent);
                        getActivity().finish();
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

        return view;
    }

    private void loadHome(Context context){
        StringRequest request = new StringRequest(Request.Method.GET, Helper.URLHome + Helper.level + "/" + Helper.id, new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                try {
                    JSONObject jsonObject = new JSONObject(response);
                    NumberFormat format = NumberFormat.getCurrencyInstance(new Locale("ID", "id"));

                    if(Helper.level.equals("siswa")){
                        Double nominal_spp = jsonObject.getDouble("nominal_spp");
                        Double total_sudah_bayar = jsonObject.getDouble("total_sudah_bayar");
                        Double total_tunggakan = jsonObject.getDouble("total_tunggakan");

                        txtNominalSpp.setText(format.format(nominal_spp));
                        txtTotalSudahBayar.setText(format.format(total_sudah_bayar));
                        txtTotalTunggakan.setText(format.format(total_tunggakan));
                        gridSiswa.setVisibility(View.VISIBLE);
                        gridPetugas.setVisibility(View.GONE);
                    } else {
                        String total_siswa = jsonObject.getString("total_siswa");
                        String total_transaksi = jsonObject.getString("total_transaksi");
                        Double total_hari_ini = jsonObject.getDouble("total_hari_ini");
                        Double total_tunggakan_siswa = jsonObject.getDouble("total_tunggakan_siswa");

                        txtPembayaranHariIni.setText(format.format(total_hari_ini));
                        txtTotalTransaksi.setText(total_transaksi);
                        txtTotalTunggakanSiswa.setText(format.format(total_tunggakan_siswa));
                        txtTotalSiswa.setText(total_siswa);
                        gridPetugas.setVisibility(View.VISIBLE);
                        gridSiswa.setVisibility(View.GONE);
                    }

                } catch (JSONException e) {
                    e.printStackTrace();
                    Helper.Alert("Error JSON", e.getMessage(), context);
                }
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                if (error.networkResponse != null && error.networkResponse.data != null) {
                    try {
                        String responseBody = new String(error.networkResponse.data, "utf-8");
                        JSONObject jsonObject = new JSONObject(responseBody);
                        JSONArray errorsArray = jsonObject.getJSONArray("message");

                        StringBuilder message = new StringBuilder();
                        for (int i = 0; i < errorsArray.length(); i++) {
                            message.append(errorsArray.getString(i));
                            if (i < errorsArray.length() - 1) {
                                message.append("\n");
                            }
                        }

                        Helper.Alert("Error", message.toString(), context);
                    } catch (Exception e) {
                        e.printStackTrace();
                        Helper.Alert("Error", "Terjadi kesalahan saat membaca respon server.", context);
                    }
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