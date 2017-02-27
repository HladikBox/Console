using AppLink.api;
using AppLink.core;
using System;
using System.Collections.Generic;
using System.Data;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace AppLink.example
{
    class Program
    {
        static void Main(string[] args)
        {


            //以下是调用的例子，请你自己替换为相关的对象，谢谢

            //获取数据库实例，超级简单工厂
            DBInstance dbmgr = DBFactory.GetInstance();
            List<Param> list = new List<Param>();


            //获取远程API实例
            APIInstance api = APIFactory.GetInstance();
            //api.SetToken("TOKEN","id");   //设置token，防止私密信息暴露
            //api.SetLang("LANG");   //设置多语言ID

            #region 列表数据搜索


            list.Add(new Param("category_id", DbType.Int32, 1));
            //list.Add(new Param("lang_code_name", DbType.String, "简体%"));
            //DataTable dt=AppLink.api.BlogMgr.list(dbmgr, list);
            //Console.WriteLine("blog/list" );
            //printDataTable(dt);

            //同步返回列表数据
            //object blogjson = BlogMgr.list(api, list);
            //printJsonReturn("blog/list", null, blogjson);

            //异步返回列表数据
            //BlogMgr.list(api, list, printJsonReturn);


            #endregion


            #region 单条数据获取
            //Console.WriteLine("blog/get");
            //int blogid =Convert.ToInt32( dt.Rows[0]["id"]);
            //Dictionary<string, object> info = AppLink.api.BlogMgr.get(dbmgr, blogid);
            //printJson(info);
            #endregion


            #region 更新存在数据
            //list.Clear();
            //list.Add(new Param("category_id", 1));
            //list.Add(new Param("name", "test csharp in name"));
            //list.Add(new Param("summary", "test csharp in summary"));
            //list.Add(new Param("published_date", "2011-11-11"));
            //list.Add(new Param("content", "test csharp in content"));
            //list.Add(new Param("status", "A"));
            //list.Add(new Param("lang_code", "1"));
            //Console.WriteLine("blog/update");
            //AppLink.api.BlogMgr.update(dbmgr, list,blogid);
            #endregion

            #region 新增数据
            //list.Clear();
            //list.Add(new Param("category_id",1));
            //list.Add(new Param("name","test csharp in name +"));
            //list.Add(new Param("summary", "test csharp in summary +"));
            //list.Add(new Param("published_date", "2011-11-11"));
            //list.Add(new Param("content", "test csharp in content +"));
            //list.Add(new Param("status", "A"));
            //list.Add(new Param("lang_code", "1"));
            //Console.WriteLine("blog/update");
            //int newblogid = AppLink.api.BlogMgr.update(dbmgr, list);
            //Console.WriteLine("new id is "+ newblogid.ToString());
            #endregion


            Console.Read();
        }



        static void printDataTable(DataTable dt)
        {
            foreach (DataRow dr in dt.Rows)
            {
                foreach (DataColumn dc in dt.Columns)
                {
                    Console.Write(dc.ColumnName + "=" + dr[dc.ColumnName].ToString() + ",");
                    Console.WriteLine();
                }
            }
        }



        static void printJsonReturn(string url, List<Param> param, Object dt)
        {
            Console.WriteLine("url:" + url + " post:" + APIInstance.ChangePostToStr(param));
            if (dt is object[])
            {
                object[] objs = (object[])dt;
                foreach (var obj in objs)
                {
                    Dictionary<string, object> dict = (Dictionary<string, object>)obj;
                    foreach (var item in dict)
                    {
                        Console.Write(item.Key + "=" + item.Value + ",");
                    }
                    Console.WriteLine();
                }
            }
            else
            {

                Dictionary<string, object> dict = (Dictionary<string, object>)dt;
                foreach (var item in dict)
                {
                    Console.Write(item.Key + "=" + item.Value + ",");
                }
                Console.WriteLine();
            }
        }
    }
}
