package com.eygle.ineedu.fragments;

import android.app.DialogFragment;
import android.app.Fragment;
import android.app.FragmentTransaction;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.AutoCompleteTextView;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Spinner;
import android.widget.Switch;
import android.widget.TextView;

import com.eygle.ineedu.R;

/**
 * Created by eygle on 7/9/15.
 */
public class FragmentSearch extends Fragment {

    EditText search;
    TextView categories;
    AutoCompleteTextView location;
    Switch dl, car;
    Button btn;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment_search, container,
                false);

        search = (EditText) view.findViewById(R.id.search);
        location = (AutoCompleteTextView) view.findViewById(R.id.location);
        categories = (TextView) view.findViewById(R.id.categories);
        dl = (Switch) view.findViewById(R.id.driver_licence_needed);
        car = (Switch) view.findViewById(R.id.car_needed);
        btn = (Button) view.findViewById(R.id.submit);

//        ArrayAdapter<CharSequence> catAdapter = ArrayAdapter.createFromResource(getActivity(), R.array.categories, android.R.layout.simple_spinner_item);
//        catAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
//        categories.setAdapter(catAdapter);

        String[] cats = getResources().getStringArray(R.array.categories);
        categories.setText(cats[0]);
        categories.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                FragmentTransaction ft = getFragmentManager().beginTransaction();
                Fragment prev = getFragmentManager().findFragmentByTag("dialog");

                if (prev != null) {
                    ft.remove(prev);
                }
                ft.addToBackStack(null);

                // Create and show the dialog.
                DialogFragment newFragment = new CategoriesAlertDialogFragment();
                newFragment.show(ft, "dialog");
            }
        });

        return view;
    }
}
