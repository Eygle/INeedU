package com.eygle.ineedu.models;

import android.widget.ListView;

/**
 * Created by eygle on 7/10/15.
 */
public class ListItem {
    public enum Type {
        ITEM, HEADER
    }

    private Type type;
    private String title;
    private int id;

    public ListItem(Type ty, String ti, int i) {
        type = ty;
        title = ti;
        id = i;
    }

    public Type getType() {
        return type;
    }

    public String getTitle() {
        return title;
    }

    public int getId() {
        return id;
    }
}
