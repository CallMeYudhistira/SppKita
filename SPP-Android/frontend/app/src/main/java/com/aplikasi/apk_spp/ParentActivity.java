package com.aplikasi.apk_spp;

import android.os.Bundle;
import android.view.MenuItem;
import android.widget.FrameLayout;

import androidx.activity.EdgeToEdge;
import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;
import androidx.fragment.app.Fragment;

import com.google.android.material.bottomnavigation.BottomNavigationView;
import com.google.android.material.navigation.NavigationBarView;

public class ParentActivity extends AppCompatActivity {

    BottomNavigationView bottomNavigationView;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_parent);

        String nisn = getIntent().getStringExtra("nisn");
        if (nisn != null) {
            DetailFragment fragment = new DetailFragment();
            Bundle bundle = new Bundle();
            bundle.putString("nisn", nisn);
            fragment.setArguments(bundle);

            getSupportFragmentManager()
                    .beginTransaction()
                    .replace(R.id.frame_container, fragment)
                    .commit();

            return;
        }

        getSupportFragmentManager().beginTransaction().replace(R.id.frame_container, new HomeFragment()).commit();
        bottomNavigationView = findViewById(R.id.bottomNavigationView);
        bottomNavigationView.setSelectedItemId(R.id.home);
        bottomNavigationView.setOnItemSelectedListener(new NavigationBarView.OnItemSelectedListener() {
            @Override
            public boolean onNavigationItemSelected(@NonNull MenuItem item) {
                Fragment selectedFragment = null;

                if (item.getItemId() == R.id.payment){
                    if (Helper.level.equals("siswa")){
                        selectedFragment = new DetailFragment();
                        Bundle bundle = new Bundle();
                        bundle.putString("nisn", Helper.id);
                        selectedFragment.setArguments(bundle);
                    } else {
                        selectedFragment = new PembayaranFragment();
                    }
                } else if (item.getItemId() == R.id.home){
                    selectedFragment = new HomeFragment();
                }

                getSupportFragmentManager().beginTransaction().replace(R.id.frame_container, selectedFragment).commit();

                return true;
            }
        });
    }
}