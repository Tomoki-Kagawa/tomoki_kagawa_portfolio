package com.example.shockmaze;

import android.content.Intent;
import android.content.pm.ActivityInfo;
import android.os.Bundle;
import androidx.appcompat.app.AppCompatActivity;
import android.view.View;
import android.widget.TextView;

public class levelActivity extends AppCompatActivity {

    public static int level = 0;
    private int retry=0;
    private String str;

    //画面の選択
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_level);
        setRequestedOrientation(ActivityInfo.SCREEN_ORIENTATION_PORTRAIT);

        //情報の受け取り
        Intent intent = getIntent();
        retry = intent.getIntExtra("retry", 0);

        if(retry==0)str="難易度を\nお選びください";
        else if (retry==1)str = "ステージ生成に\n失敗しました\nもう一度お選びください";

        TextView dataView = (TextView) findViewById(R.id.retryView);
        dataView.setText(str.toCharArray(), 0, str.length());
    }

    //画面を作る・選択
    public void onClick(View v) {
        Intent intent = new Intent(levelActivity.this, playActivity.class);
        int viewId = v.getId();
        if (viewId == R.id.level_3) {
            level = 3;
            intent.putExtra("level", level);
            startActivityForResult(intent, level);
            finish();
        }
        else if (viewId == R.id.level_2) {
            level = 2;
            intent.putExtra("level", level);
            startActivityForResult(intent, level);
            finish();
        }
        else if (viewId == R.id.level_1) {
            level = 1;
            intent.putExtra("level", level);
            startActivityForResult(intent, level);
            finish();
        }
    }
}
