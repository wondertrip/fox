function table(table){
   var _table={
           con : table.container,
          name : table.name,
            id : table.id,
           row : table.row,
           col : table.col,
    createElem : function(elem){
                   return document.createElement(elem);
                 },
     getElemId : function(id){
                   return document.getElementById(id);
                 }
   }
  // console.log(_table.createElem("table"));
   var Table=_table.createElem("table"),
       thead=_table.createElem("thead"),
       tbody=_table.createElem("tbody"),
       tableParent=_table.con;
   

   var trHead=_table.createElem("tr"); //thead
   //tr.appendChild(thead);
   for(var i=0;i<_table.col;i++){
     var th=_table.createElem("th");
     trHead.appendChild(th);
   }
   thead.appendChild(trHead);

   for(var m=0;m<_table.row;m++){
     var tr=_table.createElem("tr");
     for(var n=0;n<_table.col;n++){
        var td=_table.createElem("td");
        tr.appendChild(td);
      }
     tbody.appendChild(tr);

   }

   Table.appendChild(thead);
   Table.appendChild(tbody);
   _table.getElemId(tableParent).appendChild(Table);

}