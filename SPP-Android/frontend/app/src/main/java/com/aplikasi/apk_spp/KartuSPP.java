package com.aplikasi.apk_spp;

public class KartuSPP {
    private String bulan;
    private Double jumlah;
    private String tgl_bayar;
    private String petugas;
    private String pesan;

    public KartuSPP(String bulan, Double jumlah, String tgl_bayar, String petugas, String pesan) {
        this.bulan = bulan;
        this.jumlah = jumlah;
        this.tgl_bayar = tgl_bayar;
        this.petugas = petugas;
        this.pesan = pesan;
    }

    public String getBulan() {
        return bulan;
    }

    public Double getJumlah() {
        return jumlah;
    }

    public String getTgl_bayar() {
        return tgl_bayar;
    }

    public String getPetugas() {
        return petugas;
    }

    public String getPesan() {
        return pesan;
    }
}
