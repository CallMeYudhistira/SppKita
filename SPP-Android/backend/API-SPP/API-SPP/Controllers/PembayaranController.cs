using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using API_SPP.Helpers;
using Newtonsoft.Json;

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
            string query = "SELECT * FROM list_pembayaran WHERE 1=1";

            if (!string.IsNullOrEmpty(nama) || nama == "")
                query += $" AND nama LIKE '%{nama}%'";

            if (!string.IsNullOrEmpty(kelas) || kelas == "")
                query += $" AND id_kelas = '{kelas}'";

            DB.crud(query);
            var siswaList = JsonConvert.SerializeObject(DB.ds.Tables[0]);

            DB.crud("SELECT * FROM kelas");
            var kelasList = JsonConvert.SerializeObject(DB.ds.Tables[0]);

            return Ok(new
            {
                siswa = siswaList,
                kelas = kelasList
            });
        }
    }
}
