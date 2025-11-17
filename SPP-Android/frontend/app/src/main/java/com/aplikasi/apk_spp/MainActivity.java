package com.aplikasi.apk_spp;

import android.content.Intent;
import android.graphics.Typeface;
import android.os.Bundle;
import android.text.InputType;
import android.view.View;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.EditText;
import android.widget.Toast;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.content.res.ResourcesCompat;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

public class MainActivity extends AppCompatActivity {
    Button btnLogin;
    EditText etUsername, etPassword;
    CheckBox cbPassword;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        cbPassword = findViewById(R.id.cbPassword);
        btnLogin = findViewById(R.id.btnLogin);
        etUsername = findViewById(R.id.etUsername);
        etPassword = findViewById(R.id.etPassword);

        cbPassword.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                if (cbPassword.isChecked()) {
                    etPassword.setInputType(InputType.TYPE_CLASS_TEXT | InputType.TYPE_TEXT_VARIATION_VISIBLE_PASSWORD);
                    etPassword.setCompoundDrawablesRelativeWithIntrinsicBounds(0, 0, R.drawable.visible, 0);
                } else {
                    etPassword.setInputType(InputType.TYPE_CLASS_TEXT | InputType.TYPE_TEXT_VARIATION_PASSWORD);
                    etPassword.setCompoundDrawablesRelativeWithIntrinsicBounds(0, 0, R.drawable.hidden, 0);
                }

                Typeface nataTypeface = ResourcesCompat.getFont(getApplicationContext(), R.font.elms);
                etPassword.setTypeface(nataTypeface);

                etPassword.setSelection(etPassword.getText().length());
            }
        });

        btnLogin.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                String username = etUsername.getText().toString().trim();
                String password = etPassword.getText().toString().trim();

                loginUser(username, password);
            }
        });
    }

    private void loginUser(String username, String password) {
        JSONObject Login = new JSONObject();
        try {
            Login.put("Username", username);
            Login.put("Password", password);
        } catch (JSONException e) {
            e.printStackTrace();
        }

        JsonObjectRequest request = new JsonObjectRequest(Request.Method.POST, Helper.URLLogin, Login, new Response.Listener<JSONObject>() {
            @Override
            public void onResponse(JSONObject response) {
                try {
                    String token = response.getString("token");
                    String level = response.getString("level");

                    String data = response.getString("users");
                    JSONArray jsonArray = new JSONArray(data);
                    JSONObject user = jsonArray.getJSONObject(0);

                    if(level.equals("siswa")){
                        String nisn = user.getString("nisn");
                        String nama = user.getString("nama");

                        Helper helper = new Helper();
                        helper.save(token, nisn, nama, level);
                    } else if(level.equals("petugas") || level.equals("admin")){
                        String id = user.getString("id_petugas");
                        String nama = user.getString("nama_petugas");

                        Helper helper = new Helper();
                        helper.save(token, id, nama, level);
                    }

                    startActivity(new Intent(MainActivity.this, ParentActivity.class));
                    finish();

                    etUsername.setText("");
                    etPassword.setText("");
                } catch (JSONException e) {
                    e.printStackTrace();
                    Toast.makeText(MainActivity.this, e.getMessage(), Toast.LENGTH_SHORT).show();
                }
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                if (error.networkResponse != null && error.networkResponse.data != null) {
                    try {
                        String responseBody = new String(error.networkResponse.data, "utf-8");
                        JSONObject jsonObject = new JSONObject(responseBody);
                        String message = jsonObject.getString("message");

                        Helper.Alert("Error", message, MainActivity.this);
                    } catch (Exception e) {
                        e.printStackTrace();
                        Helper.Alert("Error", "Terjadi kesalahan saat membaca respon server.", MainActivity.this);
                    }
                } else {
                    Helper.Alert("Error", "Tidak ada koneksi internet atau server tidak merespon.", MainActivity.this);
                }
            }
        }) {
            @Override
            public Map<String, String> getHeaders(){
                Map<String, String> headers = new HashMap<>();
                headers.put("accept", "application/json");
                return headers;
            }
        };

        RequestQueue queue = Volley.newRequestQueue(this);
        queue.add(request);
    }
}