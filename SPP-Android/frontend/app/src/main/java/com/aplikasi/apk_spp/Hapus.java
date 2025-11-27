package com.aplikasi.apk_spp;

public class Hapus {
    private String nis;
    private String nama;
    private String kelas;
    private Double nominal;
    private String bulan;
    private String tgl_bayar;

    public Hapus(String nis, String nama, String kelas, Double nominal, String bulan, String tgl_bayar) {
        this.nis = nis;
        this.nama = nama;
        this.kelas = kelas;
        this.nominal = nominal;
        this.bulan = bulan;
        this.tgl_bayar = tgl_bayar;
    }

    public String getNis() {
        return nis;
    }

    public String getNama() {
        return nama;
    }

    public String getKelas() {
        return kelas;
    }

    public Double getNominal() {
        return nominal;
    }

    public String getBulan() {
        return bulan;
    }

    public String getTgl_bayar() {
        return tgl_bayar;
    }
}
