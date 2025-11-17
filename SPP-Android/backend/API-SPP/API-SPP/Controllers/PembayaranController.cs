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
        public IActionResult index()
        {
            DB.crud($"SELECT * FROM list_pembayaran");

            return Ok(new { siswa = JsonConvert.SerializeObject(DB.ds.Tables[0]), });
        }

        [HttpGet("{nama}")]
        public IActionResult search(string nama)
        {
            DB.crud($"SELECT * FROM list_pembayaran WHERE nama LIKE '%{nama}%'");

            return Ok(new { siswa = JsonConvert.SerializeObject(DB.ds.Tables[0]), });
        }
    }
}
