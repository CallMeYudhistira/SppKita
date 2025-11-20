package com.aplikasi.apk_spp;

import android.content.Context;
import android.os.Bundle;
import android.widget.ListView;
import android.widget.TextView;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;

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

public class KartuActivity extends AppCompatActivity {

    TextView tvNis, tvNama, tvKelas;
    ListView listView;
    KartuSPPAdapter adapter;
    List<KartuSPP> kartuSPPList;
    String nisn;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_kartu);

        tvNis = findViewById(R.id.tvNis);
        tvNama = findViewById(R.id.tvNama);
        tvKelas = findViewById(R.id.tvKelas);
        listView = findViewById(R.id.listView);

        kartuSPPList = new ArrayList<>();

        nisn = getIntent().getStringExtra("nisn");

        loadSiswa(KartuActivity.this);
    }

    private void loadSiswa(Context context) {
        StringRequest request = new StringRequest(Request.Method.GET, Helper.URLGetKartu + nisn, new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                try {
                    JSONObject jsonObject = new JSONObject(response);

                    String jsonString = jsonObject.getString("pembayaran");
                    JSONArray data = new JSONArray(jsonString);
                    JSONObject pembayaran = data.getJSONObject(0);

                    tvNis.setText(pembayaran.getString("nis"));
                    tvNama.setText(pembayaran.getString("nama"));
                    tvKelas.setText(pembayaran.getString("nama_kelas") + " " + pembayaran.getString("kompetensi_keahlian"));

                    data = jsonObject.getJSONArray("hasil");
                    for (int i = 0; i < data.length(); i++) {
                        JSONObject siswa = data.getJSONObject(i);
                        kartuSPPList.add(new KartuSPP(
                                siswa.getString("bulan_dibayar"),
                                Double.parseDouble(siswa.getString("jumlah_bayar")),
                                siswa.getString("tgl_bayar"),
                                siswa.getString("nama_petugas"),
                                siswa.getString("pesan")
                        ));
                    }

                    adapter = new KartuSPPAdapter(context, kartuSPPList);
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