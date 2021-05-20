package com.example.vighnesh.deliverystatus;

import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;


import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;




public class MainActivity extends AppCompatActivity implements View.OnClickListener{
EditText OrID;
Button button;
String OrderID;
String  URL="https://www.tecpanda.in/bookcart/scripts/updateDeliveryStatus.php";
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        OrID=(EditText)findViewById(R.id.txtOrderID);
        button=(Button)findViewById(R.id.buttonDelivery);
        button.setOnClickListener(this);

    }

    @Override
    public void onClick(View v) {
      if(v.getId()==R.id.buttonDelivery)    {
          OrderID=OrID.getText().toString().trim();
          StringRequest stringRequest = new StringRequest(Request.Method.POST,URL
                  ,
                  new Response.Listener<String>() {
                      @Override
                      public void onResponse(String response) {
                          try {
                              JSONObject jsonObject = new JSONObject(response);

                              Toast.makeText(getApplicationContext(), jsonObject.getString("message"), Toast.LENGTH_LONG).show();

                          } catch (JSONException e) {
                              e.printStackTrace();
                          }
                      }
                  },
                  new Response.ErrorListener() {
                      @Override
                      public void onErrorResponse(VolleyError error) {
                          Toast.makeText(getApplicationContext(), error.getMessage(), Toast.LENGTH_LONG).show();
                      }
                  }) {
              @Override
              protected Map<String, String> getParams() throws AuthFailureError {
                  Map<String, String> params = new HashMap<>();
                  params.put("OrderID", OrderID);
                  return params;

              }
          };

          RequestHandler.getInstance(this).addToRequestQueue(stringRequest);
      }

      }
    }

