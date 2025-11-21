using Microsoft.AspNetCore.Mvc;
using Microsoft.IdentityModel.Tokens;
using System.IdentityModel.Tokens.Jwt;
using System.Security.Claims;
using System.Text;
using BCrypt.Net;
using Microsoft.Extensions.Configuration;
using API_SPP.Models;
using API_SPP.Helpers;
using Newtonsoft.Json;
using System.Data;

[Route("api/[controller]")]
[ApiController]
public class AuthController : ControllerBase
{
    private readonly IConfiguration _config;

    public AuthController(IConfiguration config)
    {
        _config = config;
    }

    [HttpPost("login")]
    public IActionResult Login([FromBody] Login dto)
    {
        if (string.IsNullOrWhiteSpace(dto.Password) && string.IsNullOrWhiteSpace(dto.Username))
            return BadRequest(new { message = "Username dan Password tidak boleh kosong" });

        if (string.IsNullOrWhiteSpace(dto.Username))
            return BadRequest(new { message = "Username tidak boleh kosong" });

        if (string.IsNullOrWhiteSpace(dto.Password))
            return BadRequest(new { message = "Password tidak boleh kosong" });

        DB db = new DB();

        DataTable dtSiswa = db.Query($"SELECT * FROM siswa WHERE username = '{dto.Username}'");

        if (dtSiswa.Rows.Count == 1)
        {
            var row = dtSiswa.Rows[0];
            string dbPass = row["password"].ToString();

            if (!BCrypt.Net.BCrypt.Verify(dto.Password, dbPass))
            {
                return Unauthorized(new { message = "Password Salah" });
            }

            string token = GenerateJwtToken(dto.Username);
            string nisn = dtSiswa.Rows[0]["nisn"].ToString();
            db.Execute($"INSERT INTO logs VALUES(NULL, 'Melakukan login', NULL, '{nisn}', NOW(), NOW())");

            return Ok(new
            {
                token,
                users = JsonConvert.SerializeObject(dtSiswa),
                level = "siswa"
            });
        }

        DataTable dtPetugas = db.Query($"SELECT * FROM petugas WHERE username = '{dto.Username}'");

        if (dtPetugas.Rows.Count == 1)
        {
            var row = dtPetugas.Rows[0];
            string dbPass = row["password"].ToString();

            if (!BCrypt.Net.BCrypt.Verify(dto.Password, dbPass))
            {
                return Unauthorized(new { message = "Password Salah" });
            }

            string token = GenerateJwtToken(dto.Username);
            string id_petugas = dtPetugas.Rows[0]["id_petugas"].ToString();
            db.Execute($"INSERT INTO logs VALUES(NULL, 'Melakukan login', '{id_petugas}', NULL, NOW(), NOW())");

            return Ok(new
            {
                token,
                users = JsonConvert.SerializeObject(dtPetugas),
                level = row["level"].ToString()
            });
        }

        return Unauthorized(new { message = "Username Tidak Ditemukan" });
    }

    public class LogoutRequest
    {
        public string level { get; set; }
        public string id { get; set; }
    }

    [HttpPost("logout")]
    public IActionResult Logout([FromBody] LogoutRequest data)
    {
        DB db = new DB();

        if (data.level == "admin" || data.level == "petugas")
        {
            db.Execute($"INSERT INTO logs VALUES(NULL, 'Melakukan logout', '{data.id}', NULL, NOW(), NOW())");
        }

        if (data.level == "siswa")
        {
            db.Execute($"INSERT INTO logs VALUES(NULL, 'Melakukan logout', NULL, '{data.id}', NOW(), NOW())");
        }

        return Ok(new { message = "Logout berhasil!" });
    }

    private string GenerateJwtToken(string username)
    {
        var key = new SymmetricSecurityKey(Encoding.UTF8.GetBytes(_config["Jwt:Key"]));
        var creds = new SigningCredentials(key, SecurityAlgorithms.HmacSha256);

        var claims = new[]
        {
            new Claim(ClaimTypes.Name, username)
        };

        var token = new JwtSecurityToken(
            issuer: _config["Jwt:Issuer"],
            audience: _config["Jwt:Audience"],
            claims: claims,
            expires: System.DateTime.Now.AddHours(3),
            signingCredentials: creds);

        return new JwtSecurityTokenHandler().WriteToken(token);
    }
}
