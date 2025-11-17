package com.aplikasi.apk_spp;

public class Siswa {
    private String nisn;
    private String nis;
    private String nama;
    private String nama_kelas;
    private String kompetensi_keahlian;
    private Double nominal;
    private String status_pembayaran;

    public Siswa(String nisn, String nis, String nama, String nama_kelas, String kompetensi_keahlian, Double nominal, String status_pembayaran) {
        this.nisn = nisn;
        this.nis = nis;
        this.nama = nama;
        this.nama_kelas = nama_kelas;
        this.kompetensi_keahlian = kompetensi_keahlian;
        this.nominal = nominal;
        this.status_pembayaran = status_pembayaran;
    }

    public String getNisn() {
        return nisn;
    }

    public String getNis() {
        return nis;
    }

    public String getNama() {
        return nama;
    }

    public String getNama_kelas() {
        return nama_kelas;
    }

    public String getKompetensi_keahlian() {
        return kompetensi_keahlian;
    }

    public Double getNominal() {
        return nominal;
    }

    public String getStatus_pembayaran() {
        return status_pembayaran;
    }
}
