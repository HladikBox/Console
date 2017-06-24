DROP TABLE IF EXISTS `cities`;
CREATE TABLE `cities` ( `id` int(11) NOT NULL AUTO_INCREMENT,  `cityid` varchar(20) NOT NULL,  `city` varchar(50) NOT NULL,  `provinceid` varchar(20) NOT NULL,  `longitude` double DEFAULT NULL,  `latitude` double DEFAULT NULL,  PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=659001 DEFAULT CHARSET=utf8 COMMENT='行政区域地州市信息表';
INSERT INTO `cities` VALUES (110100,'110100','市辖区','110000',116.413677,39.910873),(110200,'110200','县','110000',116.413677,39.910873),(120100,'120100','市辖区','120000',117.209764,39.093709),(120200,'120200','县','120000',117.205295,39.79783),(130100,'130100','石家庄市','130000',114.521279,38.0483),(130200,'130200','唐山市','130000',118.186566,39.636603),(130300,'130300','秦皇岛市','130000',119.608679,39.941705),(130400,'130400','邯郸市','130000',114.545659,36.631275),(130500,'130500','邢台市','130000',114.511312,37.076654),(130600,'130600','保定市','130000',115.471091,38.880017),(130700,'130700','张家口市','130000',114.892684,40.773281),(130800,'130800','承德市','130000',117.969408,40.957906),(130900,'130900','沧州市','130000',116.845347,38.310218),(131000,'131000','廊坊市','130000',116.690279,39.543375),(131100,'131100','衡水市','130000',115.675406,37.745182),(140100,'140100','太原市','140000',112.556331,37.876972),(140200,'140200','大同市','140000',113.306548,40.082492),(140300,'140300','阳泉市','140000',113.587184,37.862367),(140400,'140400','长治市','140000',113.12293,36.201246),(140500,'140500','晋城市','140000',112.858083,35.49627),(140600,'140600','朔州市','140000',112.439292,39.337133),(140700,'140700','晋中市','140000',112.759271,37.692885),(140800,'140800','运城市','140000',111.013671,35.032677),(140900,'140900','忻州市','140000',112.740897,38.422399),(141000,'141000','临汾市','140000',111.525569,36.093775),(141100,'141100','吕梁市','140000',111.150841,37.524467),(150100,'150100','呼和浩特市','150000',111.755294,40.848405),(150200,'150200','包头市','150000',109.846694,40.662948),(150300,'150300','乌海市','150000',106.800546,39.662049),(150400,'150400','赤峰市','150000',118.895336,42.261668),(150500,'150500','通辽市','150000',122.250111,43.657948),(150600,'150600','鄂尔多斯市','150000',109.787475,39.614509),(150700,'150700','呼伦贝尔市','150000',119.772058,49.218492),(150800,'150800','巴彦淖尔市','150000',107.394196,40.749384),(150900,'150900','乌兰察布市','150000',113.139093,41.000777),(152200,'152200','兴安盟','150000',122.044217,46.088437),(152500,'152500','锡林郭勒盟','150000',116.054273,43.939407),(152900,'152900','阿拉善盟','150000',105.735377,38.858229),(210100,'210100','沈阳市','210000',123.437191,41.810777),(210200,'210200','大连市','210000',121.621602,38.91899),(210300,'210300','鞍山市','210000',123.000982,41.115031),(210400,'210400','抚顺市','210000',123.96424,41.885989),(210500,'210500','本溪市','210000',123.692514,41.492912),(210600,'210600','丹东市','210000',124.361497,40.006426),(210700,'210700','锦州市','210000',121.133073,41.100914),(210800,'210800','营口市','210000',122.241708,40.673183),(210900,'210900','阜新市','210000',121.676769,42.028006),(211000,'211000','辽阳市','210000',123.242989,41.274166),(211100,'211100','盘锦市','210000',122.077157,41.125839),(211200,'211200','铁岭市','210000',123.732268,42.229978),(211300,'211300','朝阳市','210000',120.457199,41.579792),(211400,'211400','葫芦岛市','210000',120.84322,40.717413),(220100,'220100','长春市','220000',125.330283,43.82195),(220200,'220200','吉林市','220000',126.555985,43.843521),(220300,'220300','四平市','220000',124.356784,43.172005),(220400,'220400','辽源市','220000',125.150179,42.894068),(220500,'220500','通化市','220000',125.94638,41.733844),(220600,'220600','白山市','220000',126.429548,41.939613),(220700,'220700','松原市','220000',124.831611,45.147367),(220800,'220800','白城市','220000',122.845209,45.625449),(222400,'222400','延边朝鲜族自治州','220000',129.477106,42.915778),(230100,'230100','哈尔滨市','230000',126.541527,45.808873),(230200,'230200','齐齐哈尔市','230000',123.924289,47.359982),(230300,'230300','鸡西市','230000',130.976096,45.300874),(230400,'230400','鹤岗市','230000',130.304544,47.35609),(230500,'230500','双鸭山市','230000',131.165413,46.653179),(230600,'230600','大庆市','230000',125.109071,46.593652),(230700,'230700','伊春市','230000',128.847645,47.733331),(230800,'230800','佳木斯市','230000',130.326956,46.805662),(230900,'230900','七台河市','230000',131.01165,45.776303),(231000,'231000','牡丹江市','230000',129.639756,44.55629),(231100,'231100','黑河市','230000',127.535039,50.251245),(231200,'231200','绥化市','230000',126.975033,46.660045),(232700,'232700','大兴安岭地区','230000',123.644251,52.510974),(310100,'310100','市辖区','310000',121.480524,31.23595),(310200,'310200','县','310000',121.838582,31.531424),(320100,'320100','南京市','320000',118.802759,32.064702),(320200,'320200','无锡市','320000',120.318096,31.498801),(320300,'320300','徐州市','320000',117.290952,34.212666),(320400,'320400','常州市','320000',119.981838,31.815796),(320500,'320500','苏州市','320000',120.592014,31.303584),(320600,'320600','南通市','320000',120.901335,31.986533),(320700,'320700','连云港市','320000',119.228168,34.602227),(320800,'320800','淮安市','320000',119.021885,33.616302),(320900,'320900','盐城市','320000',120.167831,33.355125),(321000,'321000','扬州市','320000',119.419392,32.400627),(321100,'321100','镇江市','320000',119.430911,32.19468),(321200,'321200','泰州市','320000',119.929345,32.460698),(321300,'321300','宿迁市','320000',118.28201,33.967709),(330100,'330100','杭州市','330000',120.162161,30.278973),(330200,'330200','宁波市','330000',121.628577,29.866027),(330300,'330300','温州市','330000',120.705991,28.00109),(330400,'330400','嘉兴市','330000',120.763105,30.751009),(330500,'330500','湖州市','330000',120.094485,30.898943),(330600,'330600','绍兴市','330000',120.585961,30.036414),(330700,'330700','金华市','330000',119.653911,29.084622),(330800,'330800','衢州市','330000',118.866519,28.975576),(330900,'330900','舟山市','330000',122.213703,29.990866),(331000,'331000','台州市','330000',121.427503,28.662153),(331100,'331100','丽水市','330000',119.929387,28.473287),(340100,'340100','合肥市','340000',117.233613,31.826626),(340200,'340200','芜湖市','340000',118.439631,31.358489),(340300,'340300','蚌埠市','340000',117.395851,32.921482),(340400,'340400','淮南市','340000',117.00622,32.631813),(340500,'340500','马鞍山市','340000',118.51335,31.676286),(340600,'340600','淮北市','340000',116.805012,33.961606),(340700,'340700','铜陵市','340000',117.818717,30.951246),(340800,'340800','安庆市','340000',117.064025,30.530983),(341000,'341000','黄山市','340000',118.345469,29.721895),(341100,'341100','滁州市','340000',118.339762,32.261286),(341200,'341200','阜阳市','340000',115.820759,32.896036),(341300,'341300','宿州市','340000',116.97091,33.652093),(341400,'341400','巢湖市','340000',117.89571,31.629033),(341500,'341500','六安市','340000',116.526309,31.741444),(341600,'341600','亳州市','340000',115.784906,33.850663),(341700,'341700','池州市','340000',117.498058,30.670927),(341800,'341800','宣城市','340000',118.765462,30.946569),(350100,'350100','福州市','350000',119.303404,26.080443),(350200,'350200','厦门市','350000',118.095995,24.485451),(350300,'350300','莆田市','350000',119.014238,25.459865),(350400,'350400','三明市','350000',117.645617,26.269732),(350500,'350500','泉州市','350000',118.682614,24.879934),(350600,'350600','漳州市','350000',117.654018,24.518876),(350700,'350700','南平市','350000',118.184555,26.64748),(350800,'350800','龙岩市','350000',117.023527,25.081207),(350900,'350900','宁德市','350000',119.554375,26.672279),(360100,'360100','南昌市','360000',115.865046,28.689424),(360200,'360200','景德镇市','360000',117.184768,29.274208),(360300,'360300','萍乡市','360000',113.861646,27.628349),(360400,'360400','九江市','360000',116.007866,29.711363),(360500,'360500','新余市','360000',114.923903,27.823598),(360600,'360600','鹰潭市','360000',117.075673,28.265813),(360700,'360700','赣州市','360000',114.940194,25.835216),(360800,'360800','吉安市','360000',115.000264,27.119693),(360900,'360900','宜春市','360000',114.423199,27.82083),(361000,'361000','抚州市','360000',116.364656,27.954887),(361100,'361100','上饶市','360000',117.949865,28.460606),(370100,'370100','济南市','370000',117.126617,36.656588),(370200,'370200','青岛市','370000',120.389622,36.072259),(370300,'370300','淄博市','370000',118.061324,36.81909),(370400,'370400','枣庄市','370000',117.330258,34.815983),(370500,'370500','东营市','370000',118.68119,37.439658),(370600,'370600','烟台市','370000',121.454239,37.470045),(370700,'370700','潍坊市','370000',119.168243,36.712618),(370800,'370800','济宁市','370000',116.593796,35.420214),(370900,'370900','泰安市','370000',117.094827,36.205819),(371000,'371000','威海市','370000',122.127197,37.516403),(371100,'371100','日照市','370000',119.53306,35.422843),(371200,'371200','莱芜市','370000',117.683275,36.219508),(371300,'371300','临沂市','370000',118.363229,35.110668),(371400,'371400','德州市','370000',116.36555,37.441299),(371500,'371500','聊城市','370000',115.992009,36.462766),(371600,'371600','滨州市','370000',117.977245,37.388204),(371700,'371700','荷泽市','370000',115.487112,35.239387),(410100,'410100','郑州市','410000',113.631732,34.753459),(410200,'410200','开封市','410000',114.314189,34.802918),(410300,'410300','洛阳市','410000',112.459897,34.624269),(410400,'410400','平顶山市','410000',113.19906,33.772078),(410500,'410500','安阳市','410000',114.399313,36.105919),(410600,'410600','鹤壁市','410000',114.303811,35.752355),(410700,'410700','新乡市','410000',113.933677,35.309648),(410800,'410800','焦作市','410000',113.248548,35.220932),(410900,'410900','濮阳市','410000',115.035623,35.767547),(411000,'411000','许昌市','410000',113.858559,34.041468),(411100,'411100','漯河市','410000',114.023071,33.5877),(411200,'411200','三门峡市','410000',111.206575,34.778358),(411300,'411300','南阳市','410000',112.5349,32.996596),(411400,'411400','商丘市','410000',115.662639,34.420248),(411500,'411500','信阳市','410000',114.097676,32.152985),(411600,'411600','周口市','410000',114.703411,33.631823),(411700,'411700','驻马店市','410000',114.028778,33.017868),(420100,'420100','武汉市','420000',114.311969,30.598516),(420200,'420200','黄石市','420000',115.04541,30.205248),(420300,'420300','十堰市','420000',110.804492,32.635047),(420500,'420500','宜昌市','420000',111.292878,30.697479),(420600,'420600','襄樊市','420000',112.129004,32.014824),(420700,'420700','鄂州市','420000',114.901668,30.396595),(420800,'420800','荆门市','420000',112.205935,31.0417),(420900,'420900','孝感市','420000',113.922962,30.930712),(421000,'421000','荆州市','420000',112.245959,30.34084),(421100,'421100','黄冈市','420000',114.878688,30.459387),(421200,'421200','咸宁市','420000',114.328922,29.847105),(421300,'421300','随州市','420000',113.389107,31.696478),(422800,'422800','恩施土家族苗族自治州','420000',109.494761,30.277896),(429000,'429000','省直辖行政单位','420000',114.348779,30.551599),(430100,'430100','长沙市','430000',112.945512,28.234856),(430200,'430200','株洲市','430000',113.140435,27.833613),(430300,'430300','湘潭市','430000',112.950473,27.83568),(430400,'430400','衡阳市','430000',112.578264,26.899529),(430500,'430500','邵阳市','430000',111.474115,27.245266),(430600,'430600','岳阳市','430000',113.135183,29.363146),(430700,'430700','常德市','430000',111.704965,29.037724),(430800,'430800','张家界市','430000',110.485635,29.122778),(430900,'430900','益阳市','430000',112.3616,28.559692),(431000,'431000','郴州市','430000',113.021195,25.776662),(431100,'431100','永州市','430000',111.619902,26.425846),(431200,'431200','怀化市','430000',110.008339,27.575176),(431300,'431300','娄底市','430000',112.001085,27.703199),(433100,'433100','湘西土家族苗族自治州','430000',109.745495,28.317355),(440100,'440100','广州市','440000',113.271283,23.135288),(440200,'440200','韶关市','440000',113.603719,24.815876),(440300,'440300','深圳市','440000',114.06444,22.548488),(440400,'440400','珠海市','440000',113.582692,22.276533),(440500,'440500','汕头市','440000',116.68877,23.359128),(440600,'440600','佛山市','440000',113.128436,23.027721),(440700,'440700','江门市','440000',113.088554,22.584617),(440800,'440800','湛江市','440000',110.366006,21.276688),(440900,'440900','茂名市','440000',110.931966,21.669082),(441200,'441200','肇庆市','440000',112.471358,23.052891),(441300,'441300','惠州市','440000',114.423552,23.116376),(441400,'441400','梅州市','440000',116.129232,24.294165),(441500,'441500','汕尾市','440000',115.381959,22.791215),(441600,'441600','河源市','440000',114.706998,23.749697),(441700,'441700','阳江市','440000',111.98879,21.864354),(441800,'441800','清远市','440000',113.062623,23.688229),(441900,'441900','东莞市','440000',113.758242,23.027266),(442000,'442000','中山市','440000',113.399251,22.522267),(445100,'445100','潮州市','440000',116.629715,23.662625),(445200,'445200','揭阳市','440000',116.37899,23.555711),(445300,'445300','云浮市','440000',112.05121,22.920917),(450100,'450100','南宁市','450000',108.373029,22.822608),(450200,'450200','柳州市','450000',109.434862,24.331992),(450300,'450300','桂林市','450000',110.296446,25.279924),(450400,'450400','梧州市','450000',111.285747,23.482755),(450500,'450500','北海市','450000',109.126468,21.486877),(450600,'450600','防城港市','450000',108.360491,21.692978),(450700,'450700','钦州市','450000',108.660924,21.986551),(450800,'450800','贵港市','450000',109.605344,23.11742),(450900,'450900','玉林市','450000',110.188043,22.659878),(451000,'451000','百色市','450000',106.624494,23.908139),(451100,'451100','贺州市','450000',111.573057,24.409402),(451200,'451200','河池市','450000',108.091892,24.698871),(451300,'451300','来宾市','450000',109.227717,23.756519),(451400,'451400','崇左市','450000',107.371478,22.383107),(460100,'460100','海口市','460000',110.325212,20.044021),(460200,'460200','三亚市','460000',109.518414,18.258729),(469000,'469000','省直辖县级行政单位','460000',108.688681,19.109273),(500100,'500100','市辖区','500000',106.558464,29.569015),(500200,'500200','县','500000',108.12052,30.006097),(500300,'500300','市','500000',106.558464,29.569015),(510100,'510100','成都市','510000',104.081592,30.655831),(510300,'510300','自贡市','510000',104.784867,29.345551),(510400,'510400','攀枝花市','510000',101.72507,26.588036),(510500,'510500','泸州市','510000',105.44877,28.877634),(510600,'510600','德阳市','510000',104.404507,31.13309),(510700,'510700','绵阳市','510000',104.685397,31.473621),(510800,'510800','广元市','510000',105.850177,32.441644),(510900,'510900','遂宁市','510000',105.599342,30.539052),(511000,'511000','内江市','510000',105.065015,29.585901),(511100,'511100','乐山市','510000',103.772219,29.557962),(511300,'511300','南充市','510000',106.117036,30.843803),(511400,'511400','眉山市','510000',103.856763,30.082492),(511500,'511500','宜宾市','510000',104.649404,28.758044),(511600,'511600','广安市','510000',106.639778,30.461737),(511700,'511700','达州市','510000',107.47478,31.214336),(511800,'511800','雅安市','510000',103.049444,30.016834),(511900,'511900','巴中市','510000',106.752038,31.872874),(512000,'512000','资阳市','510000',104.63427,30.134991),(513200,'513200','阿坝藏族羌族自治州','510000',102.231021,31.9055),(513300,'513300','甘孜藏族自治州','510000',101.968977,30.055319),(513400,'513400','凉山彝族自治州','510000',102.273806,27.887714),(520100,'520100','贵阳市','520000',106.636904,26.653294),(520200,'520200','六盘水市','520000',104.837217,26.598819),(520300,'520300','遵义市','520000',106.933839,27.731748),(520400,'520400','安顺市','520000',105.954005,26.259286),(522200,'522200','铜仁地区','520000',109.196039,27.737762),(522300,'522300','黔西南布依族苗族自治州','520000',104.912905,25.093923),(522400,'522400','毕节地区','520000',105.298959,27.290243),(522600,'522600','黔东南苗族侗族自治州','520000',107.989372,26.589715),(522700,'522700','黔南布依族苗族自治州','520000',107.528695,26.260573),(530100,'530100','昆明市','530000',102.716191,25.051514),(530300,'530300','曲靖市','530000',103.802436,25.496392),(530400,'530400','玉溪市','530000',102.553148,24.357699),(530500,'530500','保山市','530000',99.177284,25.139026),(530600,'530600','昭通市','530000',103.723282,27.344102),(530700,'530700','丽江市','530000',100.232735,26.860677),(530800,'530800','思茅市','530000',100.96603,22.759083),(530900,'530900','临沧市','530000',100.0954,23.890477),(532300,'532300','楚雄彝族自治州','530000',101.534362,25.051773),(532500,'532500','红河哈尼族彝族自治州','530000',103.382012,23.369961),(532600,'532600','文山壮族苗族自治州','530000',104.222808,23.405969),(532800,'532800','西双版纳傣族自治州','530000',100.803909,22.013569),(532900,'532900','大理白族自治州','530000',100.274305,25.612099),(533100,'533100','德宏傣族景颇族自治州','530000',98.591403,24.437974),(533300,'533300','怒江傈僳族自治州','530000',98.863247,25.823665),(533400,'533400','迪庆藏族自治州','530000',99.709513,27.825203),(540100,'540100','拉萨市','540000',91.120787,29.650002),(542100,'542100','昌都地区','540000',97.179602,31.14736),(542200,'542200','山南地区','540000',91.778668,29.243035),(542300,'542300','日喀则地区','540000',88.893742,29.275686),(542400,'542400','那曲地区','540000',92.057303,31.482481),(542500,'542500','阿里地区','540000',80.112773,32.506858),(542600,'542600','林芝地区','540000',94.368102,29.654044),(610100,'610100','西安市','610000',108.946107,34.347281),(610200,'610200','铜川市','610000',108.952008,34.902625),(610300,'610300','宝鸡市','610000',107.244371,34.368915),(610400,'610400','咸阳市','610000',108.715579,34.335465),(610500,'610500','渭南市','610000',109.516471,34.50574),(610600,'610600','延安市','610000',109.496326,36.591161),(610700,'610700','汉中市','610000',107.029748,33.073798),(610800,'610800','榆林市','610000',109.741574,38.290908),(610900,'610900','安康市','610000',109.03562,32.690534),(611000,'611000','商洛市','610000',109.924812,33.878594),(620100,'620100','兰州市','620000',103.840872,36.067184),(620200,'620200','嘉峪关市','620000',98.296239,39.777992),(620300,'620300','金昌市','620000',102.194541,38.525814),(620400,'620400','白银市','620000',104.143992,36.550848),(620500,'620500','天水市','620000',105.731316,34.58737),(620600,'620600','武威市','620000',102.64461,37.934378),(620700,'620700','张掖市','620000',100.456503,38.932045),(620800,'620800','平凉市','620000',106.671696,35.54919),(620900,'620900','酒泉市','620000',98.500693,39.738494),(621000,'621000','庆阳市','620000',107.649808,35.715234),(621100,'621100','定西市','620000',104.632397,35.586853),(621200,'621200','陇南市','620000',104.928497,33.406619),(622900,'622900','临夏回族自治州','620000',103.216799,35.607579),(623000,'623000','甘南藏族自治州','620000',102.917542,34.989093),(630100,'630100','西宁市','630000',101.78417,36.623363),(632100,'632100','海东地区','630000',102.110763,36.508517),(632200,'632200','海北藏族自治州','630000',100.907386,36.960615),(632300,'632300','黄南藏族自治州','630000',102.022059,35.525809),(632500,'632500','海南藏族自治州','630000',100.62697,36.292117),(632600,'632600','果洛藏族自治州','630000',100.251215,34.477193),(632700,'632700','玉树藏族自治州','630000',97.01318,33.010957),(632800,'632800','海西蒙古族藏族自治州','630000',97.376277,37.382726),(640100,'640100','银川市','640000',106.238939,38.492495),(640200,'640200','石嘴山市','640000',106.390644,38.989652),(640300,'640300','吴忠市','640000',106.205104,38.003708),(640400,'640400','固原市','640000',106.249053,36.021635),(640500,'640500','中卫市','640000',105.203321,37.505721),(650100,'650100','乌鲁木齐市','650000',87.624435,43.830717),(650200,'650200','克拉玛依市','650000',84.895919,45.585642),(652100,'652100','吐鲁番地区','650000',89.19727,42.957027),(652200,'652200','哈密地区','650000',93.521212,42.825827),(652300,'652300','昌吉回族自治州','650000',87.314984,44.016843),(652700,'652700','博尔塔拉蒙古自治州','650000',82.07291,44.912209),(652800,'652800','巴音郭楞蒙古自治州','650000',86.151716,41.770298),(652900,'652900','阿克苏地区','650000',80.266952,41.175001),(653000,'653000','克孜勒苏柯尔克孜自治州','650000',76.174271,39.72047),(653100,'653100','喀什地区','650000',75.996372,39.476108),(653200,'653200','和田地区','650000',79.928524,37.120408),(654000,'654000','伊犁哈萨克自治州','650000',81.330573,43.92276),(654200,'654200','塔城地区','650000',82.987214,46.750987),(654300,'654300','阿勒泰地区','650000',88.147913,47.850719),(659000,'659000','省直辖行政单位','650000',87.633424,43.799256);