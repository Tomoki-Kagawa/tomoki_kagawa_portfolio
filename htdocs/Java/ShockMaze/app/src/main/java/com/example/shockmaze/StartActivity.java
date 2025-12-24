package com.example.shockmaze;

import android.content.Intent;
import android.content.pm.ActivityInfo;
import android.os.Bundle;
import androidx.appcompat.app.AppCompatActivity;
import android.view.View;

public class StartActivity extends AppCompatActivity {

    //画面を作る・選択
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_start);
        setRequestedOrientation(ActivityInfo.SCREEN_ORIENTATION_PORTRAIT);
    }

    //ボタンクリック処理
    public void onClick(View v) {
        int viewId = v.getId();
        if (viewId == R.id.start) {
            Intent intent = new Intent(StartActivity.this, levelActivity.class);
            startActivity(intent);
            finish();
        }
    }
}