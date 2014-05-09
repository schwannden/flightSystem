function tableToArray( tableId )
{
  flight_table = document.getElementById(tableId).rows;
  result = []
  for( i = 1 ; i < flight_table.length ; i++ )
  {
    result[i-1] = [];
    result[i-1][0] = flight_table[i].cells[0].firstElementChild.value + " ";
    result[i-1][1] = flight_table[i].cells[1].firstElementChild.value + " ";
    result[i-1][2] = flight_table[i].cells[2].firstElementChild.value + " ";
    result[i-1][3] = flight_table[i].cells[3].firstElementChild.value + " ";
    result[i-1][4] = flight_table[i].cells[4].firstElementChild.value + " ";
    result[i-1][5] = flight_table[i].cells[5].firstElementChild.value + " ";
  }
  return result;
}

function arrayToTable( array, tableId ) {
  flight_table = document.getElementById(tableId).rows;
  for( i = 1 ; i <= array.length ; i++ )
  {
    flight_table[i].cells[0].firstElementChild.value = array[i-1][0];
    flight_table[i].cells[1].firstElementChild.value = array[i-1][1];
    flight_table[i].cells[2].firstElementChild.value = array[i-1][2];
    flight_table[i].cells[3].firstElementChild.value = array[i-1][3];
    flight_table[i].cells[4].firstElementChild.value = array[i-1][4];
    flight_table[i].cells[5].firstElementChild.value = array[i-1][5];
  }
}

function $(id)
{
  return document.getElementById(id);
}

function errorHandler(message, url, line)
{
  var out;
  out  = "Sorry, an error was encountered.\\n\\n";
  out += "Error: " + message + "\\n";
  out += "URL: " + url + "\\n";
  out += "LINE: " + line + "\\n";
  out += "Click \"OK\" to continue.\\n";
  alert( out );
  return true;
}

function ajaxRequest()
{
  try //non-IE
  {
    var request = new XMLHttpRequest();
  }
  catch( e1 )
  {
    try //IE 6+
    {
      request = new ActiveXObject( "Msxm12.XMLHTTP" );
    }
    catch( e2 )
    {
      try //IE 5
      {
        request = new ActiveXObject( "Microsoft.XMLJTTP" );
      }
      catch( e3 ) //no ajax support
      {
        request = false;
      }
    }
  }
  return request;
}
