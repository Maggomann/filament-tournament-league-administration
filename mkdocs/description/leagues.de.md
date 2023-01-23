# Ligen

Die Ligen sind dem Liga untergeordnet.

Derzeit können die folgenden Attribute über das Formular angezeigt oder bearbeitet werden.

## Attributtabelle

| Feld            | Typ                   | Beschreibung                                                                        | Erforderlich    |
| --------------- | --------------------- | ----------------------------------------------------------------------------------- | --------------- |
| Liga         | Select Box            | Wählen Sie den Liga aus, der Sie die Liga zuordnen möchten                       | ja              |
| Name            | Text input            | Der Name des Ligas                                                               | ja              |
| Slug            | Text input (readonly) | Der Slug wird automatisch aus dem Namen generiert                                   | ja /automatisch |
| Kalkulationstyp | Select Box            | Die [Berechnungsart](calculation-types.de.md), nach der die Spiele berechnet werden | ja              |
| Upload          | File upload           | Hier können Sie ein Bild zum Liga hochladen                                      | nein            |

---

## Liga erstellen

### Saisons & Turniere | Ligen

Eine Liga kann im Bereich `Saisons & Turniere | Ligen` über den Button **Erstellen** angelegt werden. Durch Bestätigen des Buttons `Erstellen` gelangen Sie zur Formulareingabe und können nach erfolgreichem Ausfüllen des Formulars durch Anklicken des Buttons `Erstellen` oder `Erstellen & weiterer Eintrag` die Liga anlegen.

!!! info
	Die Formular-Eingabemöglichkeiten können Sie der o. g. [Attributtabelle](#attributtabelle) entnehmen.

!!! tip " `Erstellen` oder `Erstellen & weiterer Eintrag`"
	Ein Klick auf die Schaltfläche `Erstellen` führt Sie nach der Erstellung direkt zur Bearbeitungsseite des Datensatzes.

	Ein Klick auf `Erstellen & weiterer Eintrag` bringt Sie zurück zum Formular Liga erstellen, wo Sie einen weiteren Datensatz eingeben können.

---

## Liga editieren

Sie können die Bearbeitungsseite einer Liga über die Auflistungstabelle im Bereich  `Saisons & Turniere | Ligen` aufrufen. Hier können Sie den gewünschten Datensatz zur Bearbeitung auswählen und auf das Bearbeitungssymbol klicken. Wenn Sie auf das Bearbeitungssymbol klicken, gelangen Sie zum Bearbeitungsformular.

![](../assets/edit.png)

!!! info
	Die Formular-Eingabemöglichkeiten können Sie der o. g. [Attributtabelle](#attributtabelle) entnehmen.

---

## Liga betrachten

Sie können die Ansichtsseite einer Liga über die Auflistungstabelle im Bereich `Saisons & Turniere | Ligen` aufrufen. Hier können Sie den gewünschten Datensatz zur Ansicht auswählen und auf das Ansichtssymbol klicken. Wenn Sie auf das Ansichtssymbol klicken, wird der Datensatz in einem Dialogfenster angezeigt.

---

## Liga löschen

Sie können einzelne Datensätze, eine Gruppe von Datensätzen oder alle Datensätze löschen.

### Über die Auflistungstabelle

Standardmäßig können Sie Zuordnungen in der Auflistungstabelle als Ganzes löschen. Sie können aber auch einzelne Datensätze aus Ihrer Auflistungstabelle löschen, indem Sie auf das Mülleimersymbol klicken.

---

### Über das Bearbeitungsformular ausgehend von der Auflistungstabelle

Sie können die Bearbeitungsseite einer Liga über die Auflistungstabelle im Bereich  `Saisons & Turniere | Ligen` aufrufen.  Hier haben Sie die Möglichkeit, den Datensatz zu entfernen, indem Sie die Schaltfläche **Löschen** bestätigen.

!!! danger
	Jeder Löschvorgang wird erst nach erfolgreicher Bestätigung der zuvor angezeigten Sicherheitsabfrage durchgeführt. Wird die Sicherheitsabfrage abgebrochen, wird auch der Löschvorgang nicht ausgeführt.
