using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using API_SPP.Helpers;

namespace API_SPP.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    [Authorize]
    public class HomeController : ControllerBase
    {
        [HttpGet("{level}/{id}")]
        public IActionResult dashboard(string level, string id)
        {
            if(level == "siswa")
            {
                DB.crud($"CALL dashboard_siswa('{id}')");
                var result = DB.ds.Tables[0].Rows[0];

                return Ok(new { 
                    nominal_spp = result["nominal_spp"],
                    total_sudah_bayar = result["total_sudah_bayar"],
                    total_tunggakan = result["total_tunggakan"],
                });
            }
            else
            {
                DB.crud($"SELECT * FROM dashboard_petugas");
                var result = DB.ds.Tables[0].Rows[0];

                return Ok(new
                {
                    total_siswa = result["total_siswa"],
                    total_transaksi = result["total_transaksi"],
                    total_hari_ini = result["total_hari_ini"],
                    total_tunggakan_siswa = result["total_tunggakan_siswa"],
                });
            }
        }
    }
}
