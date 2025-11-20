package com.aplikasi.apk_spp;

import android.content.Context;
import android.content.Intent;
import android.graphics.Canvas;
import android.graphics.pdf.PdfDocument;
import android.net.Uri;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.content.FileProvider;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;

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

import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.text.NumberFormat;
import java.time.LocalDate;
import java.time.format.DateTimeFormatter;
import java.util.HashMap;
import java.util.Locale;
import java.util.Map;

public class InvoiceActivity extends AppCompatActivity {

    private TextView tvTanggal, tvNis, tvNama, tvKelas, tvTahun, tvBulan, tvNominal, tvPetugas;
    private String id_pembayaran, nisn;
    private Button btnSave, btnClose, btnShare;
    private File file;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_invoice);

        tvTanggal = findViewById(R.id.tvTanggal);
        tvNis = findViewById(R.id.tvNis);
        tvNama = findViewById(R.id.tvNama);
        tvKelas = findViewById(R.id.tvKelas);
        tvTahun = findViewById(R.id.tvTahun);
        tvBulan = findViewById(R.id.tvBulan);
        tvNominal = findViewById(R.id.tvNominal);
        tvPetugas = findViewById(R.id.tvPetugas);

        btnSave = findViewById(R.id.btnSave);
        btnClose = findViewById(R.id.btnClose);
        btnShare = findViewById(R.id.btnShare);

        btnClose.setOnClickListener(v -> {
            Intent intent = new Intent(InvoiceActivity.this, ParentActivity.class);
            intent.putExtra("nisn", nisn);
            intent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
            startActivity(intent);
            finish();
        });

        btnSave.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                generatePdf();
            }
        });

        btnShare.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                sharePdf(file);
            }
        });

        id_pembayaran = getIntent().getStringExtra("id_pembayaran");

        loadInvoice();
    }

    private void generatePdf(){
        View view = findViewById(R.id.invoiceView);

        int width = view.getWidth();
        int height = view.getHeight();

        PdfDocument pdfDocument = new PdfDocument();
        PdfDocument.PageInfo pageInfo = new PdfDocument.PageInfo.Builder(width, height, 1).create();
        PdfDocument.Page page = pdfDocument.startPage(pageInfo);

        Canvas canvas = page.getCanvas();
        view.draw(canvas);

        pdfDocument.finishPage(page);

        String fileName = "invoice_" + nisn + "_bulan_" + tvBulan.getText() + ".pdf";
        File pdf = new File(getExternalFilesDir(null), fileName);
        try {
            FileOutputStream fos = new FileOutputStream(pdf);
            pdfDocument.writeTo(fos);
            file = pdf;
            Toast.makeText(this, "PDF Disimpan: " + pdf.getAbsolutePath(), Toast.LENGTH_SHORT).show();
        } catch (IOException e) {
            Toast.makeText(this, "Gagal menyimpan PDF: " + e.getMessage(), Toast.LENGTH_SHORT).show();
        }

        pdfDocument.close();
    }

    private void sharePdf(File file){
        if(file != null){
            Uri uri = FileProvider.getUriForFile(
                    InvoiceActivity.this,
                    getPackageName() + ".provider",
                    file
            );

            Intent shareIntent = new Intent(Intent.ACTION_SEND);
            shareIntent.setType("application/pdf");
            shareIntent.putExtra(Intent.EXTRA_STREAM, uri);
            shareIntent.addFlags(Intent.FLAG_GRANT_READ_URI_PERMISSION);
            startActivity(Intent.createChooser(shareIntent, "Bagikan Invoice"));
        } else {
            Toast.makeText(InvoiceActivity.this, "Simpan Transaksi Terlebih Dahulu!", Toast.LENGTH_SHORT).show();
        }
    }

    private void loadInvoice() {
        StringRequest request = new StringRequest(Request.Method.GET, Helper.URLGetInvoice + id_pembayaran, new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                try {
                    JSONObject jsonObject = new JSONObject(response);
                    String data = jsonObject.getString("invoice");
                    JSONArray jsonArray = new JSONArray(data);
                    JSONObject invoice = jsonArray.getJSONObject(0);
                    NumberFormat format = NumberFormat.getCurrencyInstance(new Locale("ID", "id"));

                    DateTimeFormatter formatter = DateTimeFormatter.ofPattern("EEEE, dd MMMM yyyy", new Locale("in", "ID"));
                    LocalDate tanggal = LocalDate.parse(invoice.getString("tgl_bayar").replace("T00:00:00", ""));
                    String formattedDate = tanggal.format(formatter);
                    tvTanggal.setText(formattedDate);

                    nisn = invoice.getString("nisn");
                    tvNis.setText(invoice.getString("nis"));
                    tvNama.setText(invoice.getString("nama"));
                    tvKelas.setText(invoice.getString("nama_kelas") + " " + invoice.getString("kompetensi_keahlian"));
                    tvTahun.setText(invoice.getString("tahun"));
                    tvBulan.setText(invoice.getString("bulan_dibayar"));
                    tvNominal.setText(format.format(invoice.getDouble("jumlah_bayar")));
                    tvPetugas.setText(invoice.getString("nama_petugas"));
                } catch (JSONException e) {
                    e.printStackTrace();
                    Helper.Alert("Error JSON", e.getMessage(), InvoiceActivity.this);
                }
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                if (error.networkResponse != null && error.networkResponse.data != null) {
                    if(error.networkResponse.statusCode == 401){
                        Helper.Alert("Error", "Token Kedaluwarsa, Silahkan Login Kembali", InvoiceActivity.this);
                        return;
                    }
                    Helper.Alert("Error", "Terjadi kesalahan saat membaca respon server.", InvoiceActivity.this);
                } else {
                    Helper.Alert("Error", "Tidak ada koneksi internet atau server tidak merespon.", InvoiceActivity.this);
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

        RequestQueue queue = Volley.newRequestQueue(this);
        queue.add(request);
    }
}