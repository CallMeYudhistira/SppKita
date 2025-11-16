using MySql.Data.MySqlClient;
using System;
using System.Collections.Generic;
using System.Data;
using System.Linq;
using System.Threading.Tasks;

namespace API_SPP.Helpers
{
    public class DB
    {
        public static MySqlConnection koneksi = new MySqlConnection("server=127.0.0.1;username=root;password=;database=db_spp");
        public static MySqlDataAdapter da;
        public static MySqlCommand perintah;
        public static DataSet ds = new DataSet();

        public static void crud(string sql)
        {
            ds.Tables.Clear();
            perintah = new MySqlCommand(sql, koneksi);
            da = new MySqlDataAdapter(perintah);
            da.Fill(ds);
        }
    }
}
