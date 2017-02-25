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

            DBInstance dbmgr = DBFactory.GetInstance();
            DataTable dt=test.atest(dbmgr);
            foreach (DataRow item in dt.Rows)
            {
                Console.WriteLine(item[0].ToString());
            }

            APIInstance api = APIFactory.GetInstance();
            List<Param> lst = new List<Param>();
            lst.Add(new Param("id", "2"));
            //Object obj= (Dictionary<string, string>)test.debugoauth(api, lst);
            Dictionary<string,string> dic = (Dictionary<string, string>)test.debugoauth(api, lst);

            string id = dic["id"].ToString();
            string token = dic["token"].ToString();
            api.SetToken(token, id);

            Dictionary<string, string> d = (Dictionary<string, string>)test.info(api, null);
            foreach (var item in d)
            {
                Console.Write(item.Key + "=" + item.Value);
            }


            test.atest(api, lst, asyncgetDataTable);

            Console.Read();
        }

        static void asyncgetDataTable(Object dt)
        {
            
        }
    }
}
