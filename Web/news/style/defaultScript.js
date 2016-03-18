
function defaultWin(url,name,w,h)
{
   try{
         var opt ="width=" + w + ",height=" + h + ",toolbar=no,menubar=no,scrollbars=yes,resizable=yes,location=no,status=yes";
         OpenWinSelf(url,name,opt,(screen.width-w)/2,(screen.height-h)/2);
    }catch(e){}
}

function golistpage(url){
    window.location.href=url;
}


function goflink(lb){
    window.location.href="./link.aspx?lb="+lb;
}

function goly(ssfs,gjz,sslb){
    window.location.href="./LeaveWord.aspx?ssfs="+ssfs+"&gjz="+gjz+"&sslb="+sslb;
}

function gossjg(form1,url){
    form1.action=url;
    form1.submit();
}


function imgchange(img,imgElement){
    if(imgElement){
        imgElement.src="./_images/lyimg/"+img;
    }
}

function ly_submit(){
    if(document.all.txt_lyzt.value==""){
        alert("请输入留言主题!")
        document.all.txt_lyzt.focus();
        return false;
    }
    if(eWebEditor1.getText()==""){
        alert("请输入内容!")
        return false;
    }
    if(document.all.txt_xm.value==""){
        alert("请输入姓名!")
        document.all.txt_xm.focus();
        return false;
    }
    if(document.all.lx.value=="0"){
        if(document.all.sel_lb.value==""){
            alert("请选择留言类别！")
            document.all.sel_lb.focus();
            return false;
        }
    }
    if(document.all.txt_code.value==""){
        alert("请输入验证码!");
        document.all.txt_code.focus();
        return false;
    }
    return true;
}


function Chklogin(){
    if(document.all.UserID.value==""){
        alert("请输入用户名！")
        document.all.UserID.focus()
        return false;
    }
    if(document.all.UserPwd.value==""){
        alert("请输入密码！")
        document.all.UserPwd.focus()
        return false;
    }
    return true;
    
}

function upd_djs(xnxq,bh,xh,cy){
    var xmlhttp = new ajaxActiveXObject();
    xmlhttp.onreadystatechange =function(){
        if (xmlhttp.readyState == 4){
            if(xmlhttp.status==200){
                var sendXML=xmlhttp.responseText;
                info.innerHTML=sendXML;
            }
        }
    }
    xmlhttp.open("POST","ktcy.aspx",true);
    xmlhttp.setRequestHeader("Content-Type","text/xml");
    s=AddXmlDATA("xnxq",xnxq);
    s+=AddXmlDATA("xh",xh);
    s+=AddXmlDATA("bh",bh);
    s+=AddXmlDATA("cy",cy);
    xmlhttp.send("<root>"+s+"</root>");
    info.innerHTML="正在加载……";
}


function Gohid(theID){
    if(theID.style.display==""){
        theID.style.display="none";
    }else{
        theID.style.display="";
    }
}

function pl_submit(){
    if(eWebEditor1.getText()==""){
        alert("请输入内容!")
        return false;
    }
    if(document.all.txt_xm.value==""){
        alert("请输入昵称!")
        document.all.txt_xm.focus();
        return false;
    }
    if(document.all.txt_code.value==""){
        alert("请输入验证码!");
        document.all.txt_code.focus();
        return false;
    }
    return true;
}



//根据得到的星期数获得中文字
      function getXq(day1)
      {
         var daytext="";
         if(day1=="0")
           daytext="日";
         else if(day1=="1")
           daytext="一";
         else if(day1=="2")
           daytext="二";
         else if(day1=="3")
           daytext="三";
         else if(day1=="4")
           daytext="四";
         else if(day1=="5")
           daytext="五";
         else if(day1=="6")
           daytext="六";
           return daytext;
      }
      
      function clearText(){
        $(".txtSearch").val("");
      
      } 