package com.aplikasi.apk_spp;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.TextView;

import java.text.NumberFormat;
import java.util.List;
import java.util.Locale;

public class KartuSPPAdapter extends BaseAdapter {
    private Context context;
    private List<KartuSPP> kartuSPPList;

    public KartuSPPAdapter(Context context, List<KartuSPP> kartuSPPList) {
        this.context = context;
        this.kartuSPPList = kartuSPPList;
    }

    @Override
    public int getCount() {
        return kartuSPPList.size();
    }

    @Override
    public Object getItem(int i) {
        return kartuSPPList.get(i);
    }

    @Override
    public long getItemId(int i) {
        return i;
    }

    @Override
    public View getView(int i, View view, ViewGroup viewGroup) {
        if(view == null){
            view = LayoutInflater.from(context).inflate(R.layout.spp_list, viewGroup, false);
        }

        KartuSPP kartuSPP = kartuSPPList.get(i);
        NumberFormat format = NumberFormat.getCurrencyInstance(new Locale("ID", "id"));

        TextView tvBulan = view.findViewById(R.id.tvBulan);
        TextView tvJumlah = view.findViewById(R.id.tvJumlah);
        TextView tvTanggal = view.findViewById(R.id.tvTanggal);
        TextView tvPetugas = view.findViewById(R.id.tvPetugas);

        if (!kartuSPP.getPesan().isEmpty()) {
            tvBulan.setText(kartuSPP.getBulan());
            tvJumlah.setText(kartuSPP.getPesan());
            tvTanggal.setText("");
            tvPetugas.setText("");
        } else {
            tvBulan.setText(kartuSPP.getBulan());
            tvJumlah.setText(format.format(kartuSPP.getJumlah()));
            tvTanggal.setText(kartuSPP.getTgl_bayar());
            tvPetugas.setText(kartuSPP.getPetugas());
        }

        return view;
    }
}
