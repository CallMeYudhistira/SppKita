package com.aplikasi.apk_spp;

public class Detail {
    private String id_pembayaran;
    private String bulan_dibayar;
    private String tanggal_dibayar;
    private String nama_petugas;
    private String pesan;

    public Detail(String id_pembayaran, String bulan_dibayar, String tanggal_dibayar, String nama_petugas, String pesan) {
        this.id_pembayaran = id_pembayaran;
        this.bulan_dibayar = bulan_dibayar;
        this.tanggal_dibayar = tanggal_dibayar;
        this.nama_petugas = nama_petugas;
        this.pesan = pesan;
    }

    public String getId_pembayaran() {
        return id_pembayaran;
    }

    public String getBulan_dibayar() {
        return bulan_dibayar;
    }

    public String getTanggal_dibayar() {
        return tanggal_dibayar;
    }

    public String getNama_petugas() {
        return nama_petugas;
    }

    public String getPesan() {
        return pesan;
    }
}
