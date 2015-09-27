package com.eygle.ineedu.adapters;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Adapter;
import android.widget.ArrayAdapter;
import android.widget.TextView;

import com.eygle.ineedu.R;
import com.eygle.ineedu.models.ListItem;

import java.util.List;

/**
 * Created by eygle on 7/10/15.
 */
public class HeadersListViewAdapter extends ArrayAdapter<ListItem> {
    Context context;
    List<ListItem> list;

    public HeadersListViewAdapter(Context context, int resource, List<ListItem> objects) {
        super(context, resource, objects);
        list = objects;
        this.context = context;
    }

    @Override
    public int getViewTypeCount() {
        return 2;
    }

    @Override
    public int getItemViewType(int position) {
        return list.get(position).getType() == ListItem.Type.ITEM ? 0 : 1;
    }

    public View getView(int position, View convertView, ViewGroup parent) {
        View rowView = convertView;

        // First let's verify the convertView is not null
        if (rowView == null) {
            // This a new view we inflate the new layout
            LayoutInflater inflater = (LayoutInflater) context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
            rowView = inflater.inflate(getItemViewType(position) == 0 ? R.layout.headder_list_item : R.layout.headder_list_header, parent, false);

            // configure view holder
            ViewHolder viewHolder = new ViewHolder();
            viewHolder.title = (TextView) rowView.findViewById(R.id.title);

            rowView.setTag(viewHolder);
        }

        // fill data
        ViewHolder holder = (ViewHolder) rowView.getTag();
        ListItem i = list.get(position);

        holder.title.setText(i.getTitle());

        return rowView;
    }

    /**
     * Used for list view optimisation
     */
    static class ViewHolder {
        public TextView title;
    }
}
