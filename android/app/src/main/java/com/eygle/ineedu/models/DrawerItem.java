package com.eygle.ineedu.models;

/**
 * Created by eygle on 7/7/15.
 */
public class DrawerItem {

    private String title;
    private int imgResID;
    private ItemType type;


    public enum ItemType {
        PROFILE, ITEM, HEADER, CONNEXION
    }

    public DrawerItem(ItemType type) {
        this.type = type;
    }

    public DrawerItem(ItemType type, String title) {
        this.title = title;
        this.type = type;
    }

    public DrawerItem(ItemType type, String title, int img) {
        this.type = type;
        this.title = title;
        this.imgResID = img;
    }

    public int getImgResID() {
        return imgResID;
    }

    public String getTitle() {
        return title;
    }

    public ItemType getType() {
        return type;
    }
}
