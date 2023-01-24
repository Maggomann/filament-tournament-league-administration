# Leagues

Leagues are subordinate to the federation.

Currently, the following attributes can be viewed or edited through the form.

## Attribute table

| Feld       | Typ                   | Description                                                  | Required           |
| ---------- | --------------------- | ------------------------------------------------------------ | ------------------ |
| Federation | Select Box            | Select the federation to which you want to assign the league | yes                |
| Name       | Text input            | The name of the league                                       | yes                |
| Slug       | Text input (readpnly) | The slug is automatically generated from the name            | yes /automatically |
| Upload     | File upload           | Here you can upload a picture to the association             | no                 |

### minimum attributes

| Feld | Typ        | Description            | Required |
| ---- | ---------- | ---------------------- | -------- |
| Name | Text input | The name of the league | yes      |

---

## Create league

### Seasons & Tournaments | Leagues

A league can be created in the area `Seasons & Tournaments | Leagues` via the button **New League**. By confirming the button `New League` you will get to the form input and after successful completion of the form you can create the league by clicking the button `Create` or `Create & create another`.

![](../assets/leagues.png)

!!! info
	The form input options can be found in the above [attribute table](#attribute-table).

!!! tip " `Create` or `Create & create another`"
	A click on the `Create` button will take you directly to the edit page of the record after creation.

	A click on `Create & create another` takes you back to the create league form, where you can enter another record.

![](../assets/create_and_create_another.png)

---

### Seasons & Tournaments | Federations

A league can be added in the `Seasons & Tournaments | Federations` section of the association edit form. Currently, only the most minimal information can be entered to create or edit a league. Please refer to the [minimum attributes](#minimum-attributes) table for this information.

![](../assets/federation_create_edit_view_league_minimal.png)

![](../assets/federation_create_new_league_minimal.png)

---

## Edit league

### Seasons & Tournaments | Leagues

You can access the editing page of a league via the listing table. Here you can select the desired record for editing and click on the edit icon. Clicking the edit icon will take you to the edit form.

![](../assets/edit.png)

!!! info
	The form input options can be found in the above [attribute table](#attribute-table).
	
---

### Seasons & Tournaments | Federations

A league can be edited in the `Seasons & Tournaments | Federations` section of the association edit form. Currently, only the most minimal information can be entered to create or edit a league. Please refer to the [minimum attributes](#minimum-attributes) table for this information.

![](../assets/federation_create_edit_view_league_minimal.png)

![](../assets/federation_edit_league_minimal.png)

---

## View league

### Seasons & Tournaments | Leagues

You can access the view page of a league via the listing table in the `Seasons & Tournaments | Leagues` section. Here you can select the desired record to view and click on the view icon. When you click on the view icon, the record will be displayed in a dialog box.

![](../assets/view.png)

---

### Seasons & Tournaments | Federations

A league can be viewed in the `Seasons & Tournaments | Federations` section in the form for editing associations. Currently, only the most minimal information can be viewed here. Please refer to the  [minimum attributes](#minimum-attributes) table for this information.

![](../assets/federation_create_edit_view_league_minimal.png)

![](../assets/federation_view_league_minimal.png)

## Delete league

You can delete individual records, a group of records or all records.

### Seasons & Tournaments | Leagues

#### About the listing table

By default, you can delete assignments in the collection table as a whole. However, you can also delete individual records from your collection table by clicking the trash can icon.

![](../assets/delete_icon.png)

![](../assets/delete_selected.png)

![](../assets/select_all.png)

!!! danger
	Each deletion process is only implemented after successful confirmation of the previously displayed confirmation prompt. If the confirmation prompt is cancelled, the deletion process is also not executed.

---

#### Via the editing form starting from the list table

You can access the editing page of a league via the listing table. Here you have the possibility to remove the record by confirming the **Delete** button.

![](../assets/delete_button.png)

!!! danger
	Each deletion process is only implemented after successful confirmation of the previously displayed confirmation prompt. If the confirmation prompt is cancelled, the deletion process is also not executed.

### Seasons & Tournaments | Federations

A league can be deleted in the `Seasons & Tournaments | Federations` section of the Edit Associations form by clicking on the trash can icon.

![](../assets/federation_create_edit_view_league_minimal.png)

!!! danger
	Each deletion process is only implemented after successful confirmation of the previously displayed confirmation prompt. If the confirmation prompt is cancelled, the deletion process is also not executed.