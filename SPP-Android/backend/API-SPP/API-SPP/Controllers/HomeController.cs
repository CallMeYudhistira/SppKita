using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using System;
using System.Data;
using API_SPP.Helpers;

namespace API_SPP.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    [Authorize]
    public class HomeController : ControllerBase
    {
        [HttpGet("{level}/{id}")]
        public IActionResult Dashboard(string level, string id)
        {
            DB db = new DB();

            if (level == "siswa")
            {
                DataTable dt = db.Query($"CALL dashboard_siswa('{id}')");

                if (dt.Rows.Count == 0)
                {
                    return NotFound(new { message = "Data tidak ditemukan." });
                }

                var row = dt.Rows[0];

                return Ok(new
                {
                    nominal_spp = row["nominal_spp"],
                    total_sudah_bayar = row["total_sudah_bayar"],
                    total_tunggakan = row["total_tunggakan"],
                });
            }
            else
            {
                DataTable dt = db.Query("SELECT * FROM dashboard_petugas");

                if (dt.Rows.Count == 0)
                {
                    return NotFound(new { message = "Data tidak ditemukan." });
                }

                var row = dt.Rows[0];

                double total_today;

                try
                {
                    total_today = Convert.ToDouble(row["total_hari_ini"]);
                }
                catch
                {
                    total_today = 0;
                }

                return Ok(new
                {
                    total_siswa = row["total_siswa"],
                    total_transaksi = row["total_transaksi"],
                    total_hari_ini = total_today,
                });
            }
        }
    }
}
