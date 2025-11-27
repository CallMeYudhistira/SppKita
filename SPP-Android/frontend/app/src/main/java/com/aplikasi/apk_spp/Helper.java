package com.aplikasi.apk_spp;

import android.app.Activity;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.Nullable;
import androidx.appcompat.app.ActionBar;
import androidx.appcompat.app.AlertDialog;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONException;
import org.json.JSONObject;

import java.text.NumberFormat;
import java.util.HashMap;
import java.util.Locale;
import java.util.Map;

public class Helper {
    public static String token;
    public static String id;    // atau nisn bagi siswa
    public static String nama;
    public static String level;

    public void save(String token, String id, String nama, String level){
        this.token = token;
        this.id = id;
        this.nama = nama;
        this.level = level;
    }

    public void flush(){
        this.token = "";
        this.id = "";
        this.nama = "";
        this.level = "";
    }

    public static String IP = "http://10.109.96.197:5001/api/";

    public static String URLLogin = IP + "Auth/login";
    public static String URLLogout = IP + "Auth/logout";
    public static String URLHome = IP + "Home/";
    public static String URLGetSiswa = IP + "Pembayaran";
    public static String URLGetDetail = IP + "Pembayaran/detail/";
    public static String URLGetInvoice = IP + "Pembayaran/cetak/";
    public static String URLGetBulan = IP + "Pembayaran/bulan/";
    public static String URLPostPembayaran = IP + "Pembayaran/bayar";
    public static String URLGetKartu = IP + "Pembayaran/kartu/";
    public static String URLGetHapus = IP + "Pembayaran/hapus";

    public void logout(Context context) {
        JSONObject body = new JSONObject();
        try {
            body.put("level", level);
            body.put("id", id);
        } catch (JSONException e) {
            e.printStackTrace();
        }

        JsonObjectRequest request = new JsonObjectRequest(Request.Method.POST, URLLogout, body,
                response -> {
                    Toast.makeText(context, "Logout berhasil!", Toast.LENGTH_SHORT).show();
                    Helper helper = new Helper();
                    helper.flush();

                    Intent intent = new Intent(context, MainActivity.class);
                    intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TASK | Intent.FLAG_ACTIVITY_NEW_TASK);
                    context.startActivity(intent);
                    ((Activity) context).finish();
                },
                error -> {
                    Helper.Alert("Error", "Logout gagal!", context);
                }
        ) {
            @Override
            public Map<String, String> getHeaders() {
                Map<String, String> headers = new HashMap<>();
                headers.put("Content-Type", "application/json; charset=utf-8");
                return headers;
            }
        };

        Volley.newRequestQueue(context).add(request);
    }

    public static void Alert(String title, String message, Context context){
        AlertDialog.Builder builder = new AlertDialog.Builder(context);
        builder.setTitle(title);
        builder.setMessage(message);
        builder.setPositiveButton("OK", new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialogInterface, int i) {
                dialogInterface.dismiss();
            }
        });

        AlertDialog dialog = builder.create();
        dialog.show();
    }
}
