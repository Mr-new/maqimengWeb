//此处用i变量来表示当前图片的位置，思路：每点击一次箭头i+1或者i-1；i的值即可作为变成红点的那个
var i=0;
//此处用time变量来存储自动轮播计时器
var time=0;
//页面加载完成后执行的函数
$(function () {

    //JS代码来判断：屏幕宽度 < 屏幕高度时，即竖屏，就让页面.lingan_1元素横屏显示
    const width = document.documentElement.clientWidth; //获取当前手机屏宽
    const height = document.documentElement.clientHeight; //手机褡高
    if (width < height) { //如果宽小于高，就是代表竖屏
        const contentDOM = document.getElementById('lingan_1'); //获取lingan_1元素
        contentDOM.style.width = height + 'px';  //设置该元素的宽等于屏高
        contentDOM.style.height = width + 'px'; //设置该元素的高等于屏宽
        contentDOM.style.top = (height - width) / 2 + 'px';
        contentDOM.style.left = 0 - (height - width) / 2 + 'px';
        contentDOM.style.transform = 'rotate(90deg)'; //让该元素旋转90度，使其横屏展示
    }
    //根据手机旋转的角度来判断
    const evt = "onorientationchange" in window ? "orientationchange" : "resize"; //旋转事件
    window.addEventListener(evt, function () { //事件监听
        if (window.orientation === 90 || window.orientation === -90) { //旋转到 90 或 -90 度，即竖屏到横屏
            screen_width = height; //横屏，灵感的宽度就等于屏高
            contentDOM.style.width = height + 'px';
            contentDOM.style.height = width + 'px';
            contentDOM.style.top = '0px';
            contentDOM.style.left = '0px';
            contentDOM.style.transform = 'none'; //不旋转；
        }else{ //旋转到 180 或 0 度，即横屏到竖屏
            screen_width = height; //竖屏，灵感的宽度就等于屏高
            contentDOM.style.width = height + 'px';
            contentDOM.style.height = width + 'px';
            contentDOM.style.top = (height - width) / 2 + 'px';
            contentDOM.style.left = 0 - (height - width) / 2 + 'px';
            contentDOM.style.transform = 'rotate(90deg)'; //旋转90度
        }
    }, false);

    //禁止F12调试
    $(document).keydown(function(){
        return key(arguments[0])});
    function key(e){
        var keynum;if(window.event){
            keynum=e.keyCode;
        }else if(e.which){
            keynum=e.which;
        }
        if(keynum==123){
            window.close();
            return false;
        }
    }
    //定义K变量：方便判断用户是进入其他页面还是进入menu(菜单)页面
    var k=1;//k变量用来存储是应该向右翻页还是向左翻页的状态
    var l=0;//l变量用来存储用户当前点击的第几个页面
    $("#menu").click(function () {
        //点击menu时检测page1是否不在动画状态，如果if为真则将右侧页面显示
        if(!$(".page").is(":animated")){
            if(k==1){
                $($(".page")[l]).animate({"left":"100%"},800);
                $(this).css("background-position","15px 10px");
                k=0;
            }else{
                $($(".page")[l]).animate({"left":0},800);
                $(this).css("background-position","-33px 10px");
                k=1;
            }
        }
    })
    //点击第几个导航，展示相应的页面
    $("#nav li").click(function () {
        $("#nav li").attr("id",null);
        $(this).attr("id","current1");
        var lis=$("#nav li");
        for(var i=0;i<lis.length;i++){
            if(lis[i]==this){
                l=lis.index(this);
                $($(".page")[l]).animate({"left":0},800);
                $("#menu").css("background-position","-33px 10px");
                k=1;
            }
        }
    })
    //点击X时关闭项目详情
    $("#close").click(function () {
        $(".details_wrap").animate({"opacity":"0","top":"-95%"},400);
    })
    //点击选项卡切换项目类型
    $(".project_top li").click(function () {
        $("#style-1").children().animate({"opacity":0},200);
        $(".project_top li").attr("id",null);
        $(this).attr("id","current");
        //通过ajax调取项目缩略图数据
        $.ajax({
            url:"/index.php/Home/Index/probably",
            type:"POST",
            async:true,
            data:{
                type:$(this).children().eq(0).val()
            },
            success:function (data) {
                var obj=JSON.parse(data);
                var dom=new Array();
                for(var i=0;i<obj.length;i++){
                    dom[i]="<li style='opacity:0;'><img src="+obj[i]['m_minimg']+"><span><img src=/Public/Index/images/big.png></span><input type='hidden' value="+obj[i]['m_id']+"><p>"+obj[i]['m_title']+"</p></li>";
                }
                $("#style-1").children().remove();
                $("#style-1").append(dom);
                $("#style-1").children().animate({"opacity":1},200);
                //点击缩略图显示项目详情
                $("#style-1 li").click(function () {
                    showdetails(this);
                })
            },
        })
    })
    //点击右上角音符图标时暂停音乐再次点击播放音乐
    $("#music").click(function () {
        if ($("#audio")[0].paused){
            $("#music").css("animation-play-state","running");
            $("#audio")[0].play();
        }else{
            $("#music").css("animation-play-state","paused");
            $("#audio")[0].pause();
        }
    })

    //将第一张图片复制一份放到最后，将最后一张图片复制一份放到最前面
    var fsrc=$("#imgwrap img:first").attr("src");
    var lsrc=$("#imgwrap img:last").attr("src");
    $("#imgwrap").append("<img src="+fsrc+">");
    $("#imgwrap").prepend("<img src="+lsrc+">");

    //自动轮播
    time=setInterval(function(){
        scroll();
    },4000);
    //当鼠标移动到箭头时停止自动轮播
    $(".bannerscroll div").mouseover(function(){
        clearInterval(time);
    })
    //当鼠标离开箭头时开始自动轮播
    $(".bannerscroll div").mouseout(function(){
        time=setInterval(function(){
            scroll();
        },4000);
    })
    //当鼠标移动到白点时停止自动轮播
    $("#menu1 li").mouseover(function(){
        clearInterval(time);
    })
    //当鼠标离开白点时开始自动轮播
    $("#menu1 li").mouseout(function(){
        time=setInterval(function(){
            scroll();
        },4000);
    })
    //点击右边箭头
    $("#goright").click(function(){
        scroll();
    })
    //点击左边箭头
    $("#goleft").click(function(){
        if(!$("#imgwrap").is(":animated")){
            i--;
            if(i<=-1){
                i=2;
            }
            $("#menu1 li").css("background","#fff");
            $("#menu1 li").eq(i).css("background","#49C9DE");
            $("#imgwrap").animate({"left":"+=100%"},700,function(){
                if(parseInt($("#imgwrap").css("left"))>=0){
                    $("#imgwrap").css("left","-300%");
                }
            });
        }
    })
    //点击下面的白色点
    $("#menu1 li").click(function(){
        if(!$("#imgwrap").is(":animated")){
            $("#menu1 li").css("background","#fff");
            $(this).css("background","#49C9DE")
            //获取当前点击元素在数组中的位置，*100是因为百分比left值
            i=$("#menu1 li").index($(this));
            var k=i+1;
            $("#imgwrap").animate({"left":-k*100+"%"},700);
        }
    })
    $("#list li").click(function () {
        $("#list li").css({"border-bottom":"none","color":"#222222"});
        $(this).css({"border-bottom":"2px solid #945848","color":"#935D4D"});
        $(".scontent").children().animate({"opacity":0},200);
        $.ajax({
            url:'/index.php/Home/Index/ajaxdata',
            type:'POST',
            data:{
                id:$(this).children().val()
            },
            success:function (data) {
                var obj=JSON.parse(data);
                var arr=new Array();
                for(var i=0;i<obj.length;i++){
                    arr[i]="<div class='scblock fl'><div><img src="+obj[i]['c_image']+"></div><h3>"+obj[i]['c_firsttitle']+"</h3><p>"+obj[i]['c_lasttitle']+"</p></div>";
                }
                $(".scontent").children().remove();
                $(".scontent").append(arr);
            }
        });

    })
})
//显示项目详情
function showdetails(_this) {
    $(".details_wrap").animate({"opacity":"1","top":"5%"},400);
    //通过ajax调取项目详情数据
    $.ajax({
        url:"/index.php/Home/Index/details",
        type:"POST",
        async:true,
        data:{
            id:$(_this).children().eq(2).val()
        },
        success:function (data) {
            var obj=JSON.parse(data);
            $(".present h2").html(obj.m_title);
            $(".present h3").html(obj.m_datetime);
            $(".present a").attr("href",obj.m_url);
            $(".present p").html(obj.m_text);
            $(".details_img img").attr("src",obj.m_maximg);
        }
    })
}
//banner图片由右向左滚动函数
function scroll(){
    if(!$("#imgwrap").is(":animated")){
        i++;
        if(i>=$("#menu1 li").length){
            i=0;
        }
        $("#menu1 li").css("background","#fff");
        $("#menu1 li").eq(i).css("background","#49C9DE");
        $("#imgwrap").animate({"left":"-=100%"},700,function(){
            var w=$(window).width()*$("#menu1 li").length;
            if(parseInt($("#imgwrap").css("left"))<-w){
                $("#imgwrap").css("left","-100%");
            }
        });
    }
}
//禁用右键菜单
function doNothing(){
    window.event.returnValue=false;
    return false;
}