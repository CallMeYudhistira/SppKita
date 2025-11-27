package com.aplikasi.apk_spp;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.TextView;

import java.text.NumberFormat;
import java.time.LocalDate;
import java.time.LocalDateTime;
import java.time.format.DateTimeFormatter;
import java.util.List;
import java.util.Locale;

public class HapusAdapter extends BaseAdapter {
    private Context context;
    private List<Hapus> hapusList;

    public HapusAdapter(Context context, List<Hapus> hapusList) {
        this.context = context;
        this.hapusList = hapusList;
    }

    @Override
    public int getCount() {
        return hapusList.size();
    }

    @Override
    public Object getItem(int i) {
        return hapusList.get(i);
    }

    @Override
    public long getItemId(int i) {
        return i;
    }

    @Override
    public View getView(int i, View view, ViewGroup viewGroup) {
        if (view == null) {
            view = LayoutInflater.from(context).inflate(R.layout.hapus_list, viewGroup, false);
        }

        Hapus hapus = hapusList.get(i);
        NumberFormat format = NumberFormat.getCurrencyInstance(new Locale("id", "ID"));

        DateTimeFormatter isoFormat = DateTimeFormatter.ofPattern("yyyy-MM-dd'T'HH:mm:ss");

        DateTimeFormatter outputFmt = DateTimeFormatter.ofPattern("dd MMMM yyyy", new Locale("id", "ID"));

        TextView tvNis = view.findViewById(R.id.tvNis);
        TextView tvNama = view.findViewById(R.id.tvNama);
        TextView tvKelas = view.findViewById(R.id.tvKelas);
        TextView tvNominal = view.findViewById(R.id.tvNominal);
        TextView tvBulan = view.findViewById(R.id.tvBulan);
        TextView tvTanggal = view.findViewById(R.id.tvTanggal);

        tvNis.setText(hapus.getNis());
        tvNama.setText(hapus.getNama());
        tvKelas.setText(hapus.getKelas());
        tvNominal.setText(format.format(hapus.getNominal()));
        tvBulan.setText(hapus.getBulan());

        try {
            String raw = hapus.getTgl_bayar();

            LocalDateTime dt;

            if (raw.contains("T")) {
                dt = LocalDateTime.parse(raw, isoFormat);
            } else {
                dt = LocalDate.parse(raw).atStartOfDay();
            }

            tvTanggal.setText(dt.format(outputFmt));

        } catch (Exception e) {
            tvTanggal.setText(hapus.getTgl_bayar());
            e.printStackTrace();
        }

        return view;
    }
}
