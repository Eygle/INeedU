package com.eygle.ineedu.fragments;

import android.app.DialogFragment;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ListView;

import com.eygle.ineedu.R;
import com.eygle.ineedu.adapters.HeadersListViewAdapter;
import com.eygle.ineedu.models.ListItem;

import java.util.ArrayList;
import java.util.List;

/**
 * Created by eygle on 7/10/15.
 */
public class CategoriesAlertDialogFragment extends DialogFragment {
    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        setStyle(DialogFragment.STYLE_NO_TITLE, android.R.style.Theme_Holo_Light_Dialog);
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        View v = inflater.inflate(R.layout.fragment_dialog_categories, container, false);
        ListView lv = (ListView) v.findViewById(R.id.listview);

        String[] cat = getResources().getStringArray(R.array.categories);
        List<ListItem> list = new ArrayList<>();

        for (int i = 0; i < cat.length; ++i) {
            list.add(new ListItem(cat[i].matches("([A-Z ])+") ? ListItem.Type.HEADER : ListItem.Type.ITEM, cat[i], i));
        }

        lv.setAdapter(new HeadersListViewAdapter(getActivity(), android.R.layout.simple_list_item_1, list));
        lv.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                dismiss();
            }
        });

        return v;
    }
}
