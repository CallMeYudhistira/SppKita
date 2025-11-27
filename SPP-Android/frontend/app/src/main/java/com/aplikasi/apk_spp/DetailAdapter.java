package com.aplikasi.apk_spp;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

import java.time.LocalDate;
import java.time.LocalDateTime;
import java.time.format.DateTimeFormatter;
import java.util.List;
import java.util.Locale;

public class DetailAdapter extends BaseAdapter {
    private Context context;
    private List<Detail> detailList;

    public DetailAdapter(Context context, List<Detail> detailList) {
        this.context = context;
        this.detailList = detailList;
    }

    @Override
    public int getCount() {
        return detailList.size();
    }

    @Override
    public Object getItem(int i) {
        return detailList.get(i);
    }

    @Override
    public long getItemId(int i) {
        return i;
    }

    @Override
    public View getView(int i, View view, ViewGroup viewGroup) {
        if(view == null){
            view = LayoutInflater.from(context).inflate(R.layout.tabel_list, viewGroup, false);
        }

        Detail detail = detailList.get(i);

        TextView tvBulan = view.findViewById(R.id.tvBulan);
        TextView tvTanggal = view.findViewById(R.id.tvTanggal);
        TextView tvPetugas = view.findViewById(R.id.tvPetugas);
        Button btnCetak = view.findViewById(R.id.btnCetak);

        DateTimeFormatter inputFmt = DateTimeFormatter.ofPattern("dd/MM/yyyy HH:mm:ss", Locale.forLanguageTag("in-ID"));
        DateTimeFormatter outputFmt = DateTimeFormatter.ofPattern("dd-M-yyyy", Locale.forLanguageTag("in-ID"));

        if (!detail.getPesan().isEmpty()) {
            tvBulan.setText(detail.getBulan_dibayar());
            tvTanggal.setText(detail.getPesan());
            tvPetugas.setText("");
        } else {
            try {
                LocalDateTime dt = LocalDateTime.parse(detail.getTanggal_dibayar(), inputFmt);
                tvTanggal.setText(dt.format(outputFmt));
            } catch (Exception e) {
                tvTanggal.setText(detail.getTanggal_dibayar());
                e.printStackTrace();
            }
            tvBulan.setText(detail.getBulan_dibayar());
            tvPetugas.setText(detail.getNama_petugas());
        }

        btnCetak.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                if(detail.getPesan().isEmpty()){
                    Intent intent = new Intent(context, InvoiceActivity.class);
                    intent.putExtra("id_pembayaran", detail.getId_pembayaran());
                    context.startActivity(intent);
                    ((Activity) context).finish();
                } else {
                    Toast.makeText(context, "Belum Bayar.", Toast.LENGTH_SHORT).show();
                }
            }
        });

        return view;
    }
}
