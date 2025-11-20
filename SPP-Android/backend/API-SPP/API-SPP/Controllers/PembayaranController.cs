using API_SPP.Helpers;
using API_SPP.Models;
using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Data;

namespace API_SPP.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    [Authorize]
    public class PembayaranController : ControllerBase
    {
        [HttpGet]
        public IActionResult GetPembayaran([FromQuery] string nama, [FromQuery] string kelas)
        {
            DB db = new DB();

            string query = "SELECT * FROM list_pembayaran WHERE 1=1";

            if (!string.IsNullOrEmpty(nama))
                query += $" AND nama LIKE '%{nama}%'";

            if (!string.IsNullOrEmpty(kelas))
                query += $" AND id_kelas = '{kelas}'";

            DataTable siswaTable = db.Query(query);
            string siswaList = JsonConvert.SerializeObject(siswaTable);

            DataTable kelasTable = db.Query("SELECT * FROM kelas");
            string kelasList = JsonConvert.SerializeObject(kelasTable);

            return Ok(new
            {
                siswa = siswaList,
                kelas = kelasList
            });
        }

        [HttpGet("detail/{nisn}")]
        public IActionResult Detail(string nisn)
        {
            DB db = new DB();

            DataTable dtPembayaran = db.Query(
                $"SELECT * FROM riwayat_pembayaran WHERE nisn = '{nisn}' LIMIT 1"
            );
            string pembayaran = JsonConvert.SerializeObject(dtPembayaran);

            string queryBase =
                $"SELECT p.id_pembayaran, p.bulan_dibayar, p.tgl_bayar, t.nama_petugas " +
                $"FROM pembayaran p " +
                $"INNER JOIN petugas t ON p.id_petugas = t.id_petugas " +
                $"WHERE p.nisn = '{nisn}'";

            var bulan = new[] {
                "Juli","Agustus","September","Oktober","November","Desember",
                "Januari","Februari","Maret","April","Mei","Juni"
            };

            List<Dictionary<string, string>> hasil = new List<Dictionary<string, string>>();

            foreach (var b in bulan)
            {
                DataTable dt = db.Query(queryBase + $" AND bulan_dibayar = '{b}'");
                Dictionary<string, string> obj;

                if (dt.Rows.Count == 0)
                {
                    obj = new Dictionary<string, string>
                    {
                        ["pesan"] = "Belum Bayar",
                        ["id_pembayaran"] = "",
                        ["bulan_dibayar"] = b,
                        ["tgl_bayar"] = "",
                        ["nama_petugas"] = "",
                    };
                }
                else
                {
                    var row = dt.Rows[0];
                    obj = new Dictionary<string, string>
                    {
                        ["pesan"] = "",
                        ["id_pembayaran"] = row["id_pembayaran"].ToString(),
                        ["bulan_dibayar"] = b,
                        ["tgl_bayar"] = row["tgl_bayar"].ToString(),
                        ["nama_petugas"] = row["nama_petugas"].ToString(),
                    };
                }

                hasil.Add(obj);
            }

            return Ok(new
            {
                pembayaran,
                hasil
            });
        }

        [HttpGet("cetak/{id_pembayaran}")]
        public IActionResult CetakInvoice(string id_pembayaran)
        {
            DB db = new DB();

            DataTable dt = db.Query($"CALL cetak_invoice('{id_pembayaran}')");
            string invoice = JsonConvert.SerializeObject(dt);

            return Ok(new { invoice });
        }

        [HttpGet("bulan/{nisn}")]
        public IActionResult GetBulan(string nisn)
        {
            DB db = new DB();

            string queryBase = $"SELECT id_pembayaran, bulan_dibayar FROM pembayaran WHERE nisn = '{nisn}' AND deleted_at IS NULL";

            var bulan = new[] {
                "Juli","Agustus","September","Oktober","November","Desember",
                "Januari","Februari","Maret","April","Mei","Juni"
            };

            List<string> bulanList = new List<string>();

            foreach (var b in bulan)
            {
                DataTable dt = db.Query(queryBase + $" AND bulan_dibayar = '{b}'");

                if (dt.Rows.Count == 0)
                {
                    bulanList.Add(b);
                }
            }

            return Ok(new { bulan = bulanList });
        }

        [HttpPost("bayar")]
        public IActionResult Store([FromBody] PembayaranRequest req)
        {
            DB db = new DB();
            if (req == null || req.bulan == null || req.bulan.Count == 0)
                return BadRequest(new { message = "Request tidak valid!" });

            try
            {
                // Ambil tahun dari tabel spp
                var dtTahun = db.Query($"SELECT tahun FROM spp WHERE id_spp = {req.id_spp} LIMIT 1");

                if (dtTahun.Rows.Count == 0)
                    return BadRequest(new { message = "ID SPP tidak ditemukan!" });

                string tahun = dtTahun.Rows[0]["tahun"].ToString();

                // Loop insert pembayaran
                foreach (var b in req.bulan)
                {
                    string sql = $@"
                    INSERT INTO pembayaran 
                    (id_petugas, nisn, tgl_bayar, bulan_dibayar, tahun_dibayar, id_spp, jumlah_bayar, created_at)
                    VALUES
                    ({req.id_petugas}, '{req.nisn}', CURDATE(), '{b}', '{tahun}', {req.id_spp}, {req.nominal}, NOW())
                ";

                    db.Execute(sql);
                }

                return Ok(new { status = true, message = "Pembayaran berhasil disimpan!" });
            }
            catch (Exception ex)
            {
                return StatusCode(500, new { message = ex.Message });
            }
        }

        [HttpGet("kartu/{nisn}")]
        public IActionResult Kartu(string nisn)
        {
            DB db = new DB();

            DataTable dtPembayaran = db.Query(
                $"SELECT * FROM riwayat_pembayaran WHERE nisn = '{nisn}' LIMIT 1"
            );
            string pembayaran = JsonConvert.SerializeObject(dtPembayaran);

            string queryBase =
                $"SELECT p.id_pembayaran, p.bulan_dibayar, p.tgl_bayar, t.nama_petugas, p.jumlah_bayar " +
                $"FROM pembayaran p " +
                $"INNER JOIN petugas t ON p.id_petugas = t.id_petugas " +
                $"WHERE p.nisn = '{nisn}'";

            var bulan = new[] {
                "Juli","Agustus","September","Oktober","November","Desember",
                "Januari","Februari","Maret","April","Mei","Juni"
            };

            List<Dictionary<string, string>> hasil = new List<Dictionary<string, string>>();

            foreach (var b in bulan)
            {
                DataTable dt = db.Query(queryBase + $" AND bulan_dibayar = '{b}'");
                Dictionary<string, string> obj;

                if (dt.Rows.Count == 0)
                {
                    obj = new Dictionary<string, string>
                    {
                        ["pesan"] = "Belum Bayar",
                        ["jumlah_bayar"] = "0.0",
                        ["bulan_dibayar"] = b,
                        ["tgl_bayar"] = "",
                        ["nama_petugas"] = "",
                    };
                }
                else
                {
                    var row = dt.Rows[0];
                    obj = new Dictionary<string, string>
                    {
                        ["pesan"] = "",
                        ["jumlah_bayar"] = row["jumlah_bayar"].ToString(),
                        ["bulan_dibayar"] = b,
                        ["tgl_bayar"] = row["tgl_bayar"].ToString(),
                        ["nama_petugas"] = row["nama_petugas"].ToString(),
                    };
                }

                hasil.Add(obj);
            }

            return Ok(new
            {
                pembayaran,
                hasil
            });
        }
    }
}
