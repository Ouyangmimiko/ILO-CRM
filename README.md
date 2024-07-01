# ILO-CRM
> report editor: https://www.overleaf.com/ (please link university mail)
## Output
- 50 page group report (50%)
- 10 page individual report/reflection (30%)
- 10 min group presentation + 10 min Q&A (20%)

## prototype
https://5bu1dw.axshare.com
### Our original website
http://ilodatabase.unaux.com/

## google drive
https://drive.google.com/drive/folders/11tHrpfLI1AUKGmZiqL8i6qnZiW351JPJ?usp=drive_link

## Branch explaination
**Tutorial:** https://www.youtube.com/watch?v=u2RJXTtCn2Y (include front-end & back-end)
分集版： https://www.youtube.com/watch?v=7rxHWX730nE （这是part1，然后点旁边一集一集看就好）

All files in **ilocrm_vue** folder:
* In this folder & at terminal input: ***npm run serve***
* In src folder:

    * mocks folder is mocking back-end:

        * use **handlers.ts** to set back-end api 
    * router folder is all views router
    * index.ts in store folder keeps state
    * App.vue is whole website pages
    * Basicly all global var in main.ts
* In **components** folder (of src):

    * layout folder includes: navbar, database-view-set
    * table folder includes: base-table (for set tale), **combined-table** (recive table data from back-end & use base-table to create a table)
    * ModifyBox.vue is just a example of add item to table (need change)
  
    
If is there any question, please ask me
