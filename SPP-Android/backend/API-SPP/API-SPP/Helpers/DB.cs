using MySql.Data.MySqlClient;
using System.Data;

namespace API_SPP.Helpers
{
    public class DB
    {
        private readonly string _connString =
            "server=127.0.0.1;username=root;password=;database=db_spp";

        public DataTable Query(string sql)
        {
            using (var conn = new MySqlConnection(_connString))
            using (var cmd = new MySqlCommand(sql, conn))
            using (var da = new MySqlDataAdapter(cmd))
            {
                var dt = new DataTable();
                conn.Open();
                da.Fill(dt);
                return dt;
            }
        }

        public int Execute(string sql)
        {
            using (var conn = new MySqlConnection(_connString))
            using (var cmd = new MySqlCommand(sql, conn))
            {
                conn.Open();
                return cmd.ExecuteNonQuery();
            }
        }
    }
}
