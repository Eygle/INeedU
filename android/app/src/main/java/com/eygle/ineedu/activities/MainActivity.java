package com.eygle.ineedu.activities;

import android.app.Fragment;
import android.app.FragmentManager;
import android.content.res.Configuration;
import android.support.v4.view.GravityCompat;
import android.support.v4.widget.DrawerLayout;
import android.support.v7.app.ActionBarActivity;
import android.os.Bundle;
import android.support.v7.app.ActionBarDrawerToggle;
import android.view.MenuItem;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ListView;

import com.eygle.ineedu.R;
import com.eygle.ineedu.adapters.CustomDrawerAdapter;
import com.eygle.ineedu.fragments.FragmentGoogleMap;
import com.eygle.ineedu.fragments.FragmentOne;
import com.eygle.ineedu.fragments.FragmentSearch;
import com.eygle.ineedu.models.DrawerItem;

import java.util.ArrayList;
import java.util.List;


public class MainActivity extends ActionBarActivity {

    private DrawerLayout mDrawerLayout;
    private ListView mDrawerList;
    private ActionBarDrawerToggle mDrawerToggle;

    private CharSequence mDrawerTitle;
    private CharSequence mTitle;
    CustomDrawerAdapter adapter;

    List<DrawerItem> dataList;

    public static android.support.v4.app.FragmentManager fragmentManager;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        initMenuDrawer(savedInstanceState);

        fragmentManager = getSupportFragmentManager();
    }

    @Override
    protected void onResume() {
        super.onResume();
    }

    private void initMenuDrawer(Bundle savedInstanceState) {
        // Initializing
        dataList = new ArrayList<DrawerItem>();
        mTitle = mDrawerTitle = getTitle();
        mDrawerLayout = (DrawerLayout) findViewById(R.id.drawer_layout);
        mDrawerList = (ListView) findViewById(R.id.left_drawer);

        mDrawerLayout.setDrawerShadow(R.drawable.drawer_shadow,
                GravityCompat.START);

        // Add Drawer Item to dataList
        if (true) {// TODO add check if connected or not
            dataList.add(new DrawerItem(DrawerItem.ItemType.PROFILE));
        } else {
            // TODO connexion / subsribe
        }

        dataList.add(new DrawerItem(DrawerItem.ItemType.ITEM, getString(R.string.action_map), R.drawable.ic_action_labels));
        dataList.add(new DrawerItem(DrawerItem.ItemType.ITEM, getString(R.string.action_search), R.drawable.ic_action_search));

        if (true) {// TODO add check if connected or not
            dataList.add(new DrawerItem(DrawerItem.ItemType.ITEM,
                    getString(R.string.action_messages), R.drawable.ic_action_email));
            dataList.add(new DrawerItem(DrawerItem.ItemType.ITEM,
                    getString(R.string.action_favorites), R.drawable.ic_action_good));
        }

        dataList.add(new DrawerItem(DrawerItem.ItemType.HEADER, "Other Option")); // adding a header to the list
        dataList.add(new DrawerItem(DrawerItem.ItemType.ITEM, "About", R.drawable.ic_action_about));
        dataList.add(new DrawerItem(DrawerItem.ItemType.ITEM, "Settings", R.drawable.ic_action_settings));
        dataList.add(new DrawerItem(DrawerItem.ItemType.ITEM, "Help", R.drawable.ic_action_help));

        adapter = new CustomDrawerAdapter(this, R.layout.custom_drawer_item,
                dataList);
        mDrawerList.setAdapter(adapter);
        mDrawerList.setOnItemClickListener(new DrawerItemClickListener());

        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
        getSupportActionBar().setHomeButtonEnabled(true);

        mDrawerToggle = new ActionBarDrawerToggle(this, mDrawerLayout,
                R.string.drawer_open,
                R.string.drawer_close) {
            public void onDrawerClosed(View view) {
                getSupportActionBar().setTitle(mTitle);
                invalidateOptionsMenu(); // creates call to
                // onPrepareOptionsMenu()
            }

            public void onDrawerOpened(View drawerView) {
                getSupportActionBar().setTitle(mDrawerTitle);
                invalidateOptionsMenu(); // creates call to
                // onPrepareOptionsMenu()
            }
        };

        mDrawerLayout.setDrawerListener(mDrawerToggle);

        if (savedInstanceState == null) {
            SelectItem(1);
        }
    }

    @Override
    public void setTitle(CharSequence title) {
        mTitle = title;
        getSupportActionBar().setTitle(mTitle);
    }

    @Override
    protected void onPostCreate(Bundle savedInstanceState) {
        super.onPostCreate(savedInstanceState);
        // Sync the toggle state after onRestoreInstanceState has occurred.
        mDrawerToggle.syncState();
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        // The action bar home/up action should open or close the drawer.
        // ActionBarDrawerToggle will take care of this.
        if (mDrawerToggle.onOptionsItemSelected(item)) {
            return true;
        }

        return false;
    }

    @Override
    public void onConfigurationChanged(Configuration newConfig) {
        super.onConfigurationChanged(newConfig);
        // Pass any configuration change to the drawer toggles
        mDrawerToggle.onConfigurationChanged(newConfig);
    }

    public void SelectItem(int position) {

        Fragment fragment = null;
        Bundle args = new Bundle();

        switch (dataList.get(position).getType()) {
            case ITEM:
                switch (position) {
                    case 1:
                        fragment = new FragmentGoogleMap();
                        break;
                    case 2:
                        fragment = new FragmentSearch();
                        break;
                    default:
                        fragment = new FragmentOne();
                        args.putString(FragmentOne.ITEM_NAME, dataList.get(position)
                                .getTitle());
                        args.putInt(FragmentOne.IMAGE_RESOURCE_ID, dataList.get(position)
                                .getImgResID());
                        break;
                }
                break;
            default:
                return;
        }

        fragment.setArguments(args);
        FragmentManager frgManager = getFragmentManager();
        frgManager.beginTransaction().replace(R.id.content_frame, fragment)
                .commit();

        mDrawerList.setItemChecked(position, true);
        setTitle(dataList.get(position).getTitle());
        mDrawerLayout.closeDrawer(mDrawerList);

    }
    private class DrawerItemClickListener implements
            ListView.OnItemClickListener {
        @Override
        public void onItemClick(AdapterView<?> parent, View view, int position,
                                long id) {
            SelectItem(position);

        }
    }

//    @Override
//    public boolean onCreateOptionsMenu(Menu menu) {
//        // Inflate the menu; this adds items to the action bar if it is present.
//        getMenuInflater().inflate(R.menu.menu_main, menu);
//        return true;
//    }
}
