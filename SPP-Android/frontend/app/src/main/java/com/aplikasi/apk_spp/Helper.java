package com.aplikasi.apk_spp;

import android.content.Context;
import android.content.DialogInterface;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.ImageView;
import android.widget.TextView;

import androidx.appcompat.app.AlertDialog;

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
    public static String URLHome = IP + "Home/";
    public static String URLGetSiswa = IP + "Pembayaran";
    public static String URLGetDetail = IP + "Pembayaran/detail/";
    public static String URLGetInvoice = IP + "Pembayaran/cetak/";
    public static String URLGetBulan = IP + "Pembayaran/bulan/";
    public static String URLPostPembayaran = IP + "Pembayaran/bayar";

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
