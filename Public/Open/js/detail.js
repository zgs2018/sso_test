$(function(){
	var app = new Vue({
        el: '#openclassDetail',
        data: {
            classdata: [],
            request:{
                id:""
            },
            reco:[]
        },
        methods:{
            getreco:function(){
                var _this = this;
                $.ajax({
                    url:"/api/open",
                    type:"POST",
                    data:{is_reco:1,limit:6,order:'add_ts desc'},
                    datatype:"json",
                    success:function(res){
                        if (res.result) {
                            _this.reco = res.lists
                        }
                    }
                })
            },
            getData:function(_id){
                var _this = this;
                $.ajax({
                    url:"/api/open/detail",
                    type:"POST",
                    data:this.request,
                    datatype:"json",
                    success:function(res){
                        if (res.result) {
                            if (res.info.playback_addr === 'NEED_AUTH') {
                                res.info.playback_addr = "/user/#/login"
                            }
                            res.info.t_img_url = res.crm_domain + res.info.t_img_url
                            _this.classdata = res.info;

                            var _body = $(".tabcontent").width();
                            _this.$nextTick(function(){
                                $(".tabDv img").each(function(){
                                    if ($(this).width() > _body) {
                                        $(this).attr("width", "100%").attr("height", "auto");
                                    }
                                })
                            })
                        }
                    }
                })
            },
            getQueryString: function(name, needdecoed) {
	            var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
	            var lh = window.location.search;
	            if (needdecoed) {
	                lh = decodeURI(window.location.search)
	            }
	            var r = lh.substr(1).match(reg);
	            if (r != null) return unescape(r[2]);
	            return null;
	        }
        },
        mounted(){
            this.request.id = this.getQueryString('id')
            this.getreco();
            this.getData();
            $(".tabHeader li").click(function(){
                var _index = $(this).index();
                $(this).addClass("active").siblings().removeClass("active");
                $(".tabDv .tabcontent").eq(_index).addClass("active").siblings().removeClass("active");
            })
        }
    });
})