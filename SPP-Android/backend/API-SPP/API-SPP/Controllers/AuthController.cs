using Microsoft.AspNetCore.Mvc;
using Microsoft.IdentityModel.Tokens;
using MySql.Data.MySqlClient;
using System.IdentityModel.Tokens.Jwt;
using System.Security.Claims;
using System.Text;
using BCrypt.Net;
using Microsoft.Extensions.Configuration;
using API_SPP.Models;
using API_SPP.Helpers;
using Newtonsoft.Json;

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
        DB.crud($"SELECT * FROM siswa WHERE username = '{dto.Username}'");
        int cekSiswa = DB.ds.Tables[0].Rows.Count;

        if (cekSiswa == 1)
        {
            var reader = DB.ds.Tables[0].Rows[0];

            string dbPass = reader["password"].ToString();

            if (!BCrypt.Net.BCrypt.Verify(dto.Password, dbPass))
                return Unauthorized(new { message = "Password salah" });

            string token = GenerateJwtToken(dto.Username);

            return Ok(new { token, users = JsonConvert.SerializeObject(DB.ds.Tables[0]), level = "siswa"});
        }

        DB.crud($"SELECT * FROM petugas WHERE username = '{dto.Username}'");
        int cekPetugas = DB.ds.Tables[0].Rows.Count;

        if (cekPetugas == 1)
        {
            var reader = DB.ds.Tables[0].Rows[0];

            string dbPass = reader["password"].ToString();

            if (!BCrypt.Net.BCrypt.Verify(dto.Password, dbPass))
                return Unauthorized(new { message = "Password salah" });

            string token = GenerateJwtToken(dto.Username);

            return Ok(new { token, users = JsonConvert.SerializeObject(DB.ds.Tables[0]), level = reader["level"].ToString() });
        }

        return Unauthorized(new { message = "Username atau Password salah" });
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
