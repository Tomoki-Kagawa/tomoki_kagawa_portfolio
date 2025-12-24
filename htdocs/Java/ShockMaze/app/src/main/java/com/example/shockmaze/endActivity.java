package com.example.shockmaze;


import android.content.Intent;
import android.content.pm.ActivityInfo;
import android.os.Bundle;
import android.os.Vibrator;
//import android.support.v7.app.AppCompatActivity;
import androidx.appcompat.app.AppCompatActivity;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;

public class endActivity extends AppCompatActivity {

    private Vibrator vib;
    private int result;

    private String str;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_end);
        setRequestedOrientation(ActivityInfo.SCREEN_ORIENTATION_PORTRAIT);

        vib = (Vibrator)getSystemService(VIBRATOR_SERVICE);

        vib.vibrate(10000);
        vib.cancel();

        //情報の受け取り
        Intent intent = getIntent();
        result = intent.getIntExtra("result", 0);

        if(result==0)str="GAME\nOVER";
        else if (result==1)str = "GOAL!";

        TextView dataView = (TextView) findViewById(R.id.resultView);
        dataView.setText(str.toCharArray(), 0, str.length());

        Button btnNext1 = (Button) findViewById(R.id.end);
        btnNext1.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent = new Intent(endActivity.this,StartActivity.class);
                startActivity(intent);
                finish();
            }
        });
    }
}
