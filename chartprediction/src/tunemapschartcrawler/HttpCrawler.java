package tunemapschartcrawler;

import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.protocol.HttpContext;
import org.json.simple.parser.JSONParser;
import org.json.simple.parser.ParseException;

/**
 * The HTTP crawler
 * 
 * @author Rolf Jagerman <rolf.jagerman@contended.nl>
 */
public class HttpCrawler {
    
    /**
     * Initialization status
     */
    protected static boolean initialized = false;
    
    /**
     * The HTTP client
     */
    protected static DefaultHttpClient httpClient;
    
    /**
     * The HTTP context
     */
    protected static HttpContext httpContext;
    
    /**
     * Initializes the http crawler
     */
    public static void initialize() {
        httpClient = new DefaultHttpClient();
    }
    
    /**
     * Gets the JSON object at given URL
     * 
     * @param url The url
     * @return The JSON object
     */
    public static JsonCrawler getJson(String url) throws IOException, ParseException {
        InputStream stream = getStream(url);
        JSONParser parser = new JSONParser();
        return new JsonCrawler(parser.parse(new InputStreamReader(stream)));
    }
    
    /**
     * Gets the input stream of given content
     * 
     * @param url The url
     * @return The content as an input stream
     * @throws IOException 
     */
    public static InputStream getStream(String url) throws IOException {
        if(!initialized) {
            initialize();
        }
        
        HttpGet request = new HttpGet(url);
        HttpResponse response = httpClient.execute(request);
        HttpEntity entity = response.getEntity();
        if (entity != null) {
            return entity.getContent();
        } else {
            throw new IOException("Could not get content");
        }
        
    }
    
}
