package com.aplikasi.apk_spp;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.Button;
import android.widget.TextView;

import java.text.NumberFormat;
import java.util.List;
import java.util.Locale;

public class SiswaAdapter extends BaseAdapter {
    private Context context;
    private List<Siswa> siswaList;

    public SiswaAdapter(Context context, List<Siswa> siswaList) {
        this.context = context;
        this.siswaList = siswaList;
    }

    @Override
    public int getCount() {
        return siswaList.size();
    }

    @Override
    public Object getItem(int i) {
        return siswaList.get(i);
    }

    @Override
    public long getItemId(int i) {
        return i;
    }

    @Override
    public View getView(int i, View view, ViewGroup viewGroup) {
        if (view == null){
            view = LayoutInflater.from(context).inflate(R.layout.pay_list, viewGroup, false);
        }

        Siswa siswa = siswaList.get(i);
        NumberFormat format = NumberFormat.getCurrencyInstance(new Locale("ID", "id"));

        TextView tvNis = view.findViewById(R.id.tvNis);
        TextView tvNisn = view.findViewById(R.id.tvNisn);
        TextView tvNama = view.findViewById(R.id.tvNama);
        TextView tvKelas = view.findViewById(R.id.tvKelas);
        TextView tvNominal = view.findViewById(R.id.tvNominal);
        TextView lunas = view.findViewById(R.id.lunas);
        Button btnBayar = view.findViewById(R.id.btnBayar);

        tvNisn.setText(siswa.getNisn());
        tvNis.setText(siswa.getNis());
        tvNama.setText(siswa.getNama());
        tvKelas.setText(siswa.getNama_kelas() + " " + siswa.getKompetensi_keahlian());
        tvNominal.setText(format.format(siswa.getNominal()));

        if(siswa.getStatus_pembayaran().equals("lunas")){
            lunas.setVisibility(View.VISIBLE);
            btnBayar.setVisibility(View.GONE);
        } else if(siswa.getStatus_pembayaran().equals("belum lunas")){
            lunas.setVisibility(View.GONE);
            btnBayar.setVisibility(View.VISIBLE);
        }

        return view;
    }
}
