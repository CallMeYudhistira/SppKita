using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace API_SPP.Models
{
    public class PembayaranRequest
    {
        public string nisn { get; set; }
        public int id_spp { get; set; }
        public int id_petugas { get; set; }
        public double nominal { get; set; }
        public List<string> bulan { get; set; }
    }
}
