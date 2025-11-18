package com.aplikasi.apk_spp;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.Button;
import android.widget.TextView;

import java.util.List;

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

        tvBulan.setText(detail.getBulan_dibayar());
        tvTanggal.setText(detail.getTanggal_dibayar());
        tvPetugas.setText(detail.getNama_petugas());
        btnCetak.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                
            }
        });

        return view;
    }
}
