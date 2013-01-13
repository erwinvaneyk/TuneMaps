package tunemapschartcrawler;

import java.io.File;
import java.io.FileWriter;
import java.io.IOException;
import java.net.URLEncoder;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.logging.Level;
import java.util.logging.Logger;
import org.json.simple.parser.ParseException;

/**
 * The tune maps chart crawler java program
 * 
 * This program crawls chart information for all metros and all weeks on the
 * Last.FM website. It stores the charts per song as feature vectors that can
 * be read into matlab to perform logistic regression.
 * 
 * @author Rolf Jagerman <rolf.jagerman@contended.nl>
 */
public final class TuneMapsChartCrawler {
    
    /**
     * The metros
     */
    protected ArrayList<Metro> metros;
    
    /**
     * The weeks
     */
    protected ArrayList<Week> weeks;
    
    /**
     * The chart cache
     */
    protected Chart[][] chartCache;
    
    /**
     * The feature matrices
     */
    protected HashMap<Metro, FeatureMatrix> matrices;

    /**
     * Entry point of the application
     * 
     * @param args the command line arguments
     */
    public static void main(String[] args) {
        try {
            new TuneMapsChartCrawler().crawl();
        } catch(Exception ex) {
            ex.printStackTrace();
        }
    }
    
    /**
     * Creates the tunemaps chart crawler
     * 
     * @throws IOException
     * @throws ParseException
     * @throws JsonException 
     */
    public TuneMapsChartCrawler() throws IOException, ParseException, JsonException {
        loadMetros();
        loadWeeks();
        matrices = new HashMap<Metro, FeatureMatrix>();
        chartCache = new Chart[metros.size()][weeks.size()];
    }
    
    /**
     * Gets the list of all available metros
     * 
     * @return The list of all available metros
     * @throws IOException
     * @throws ParseException
     * @throws JsonException 
     */
    public void loadMetros() throws IOException, ParseException, JsonException {
        metros = new ArrayList<Metro>();
        JsonCrawler jc = HttpCrawler.getJson("http://ws.audioscrobbler.com/2.0/?method=geo.getmetros&api_key=dcd351ddc924b09be225a82db043311c&format=json");
        for(JsonCrawler j : jc.get("metros").get("metro").list()) {
            metros.add(new Metro(j.get("country").string(), j.get("name").string()));
            metros.get(metros.size()-1).setIndex(metros.size()-1);
        }
    }
    
    /**
     * Gets the list of all available weeks
     * 
     * @return The list of all available weeks
     * @throws IOException
     * @throws ParseException
     * @throws JsonException 
     */
    public void loadWeeks() throws IOException, ParseException, JsonException {
        weeks = new ArrayList<Week>();
        JsonCrawler jc = HttpCrawler.getJson("http://ws.audioscrobbler.com/2.0/?method=geo.getmetroweeklychartlist&api_key=dcd351ddc924b09be225a82db043311c&format=json");
        for(JsonCrawler j : jc.get("weeklychartlist").get("chart").list()) {
            weeks.add(new Week(j.get("from").integer(), j.get("to").integer()));
            weeks.get(weeks.size()-1).setIndex(weeks.size()-1);
        }
    }
    
    /**
     * Crawls for new chart entries
     */
    public void crawl() throws IOException {
        
        // Get new feature vectors for next week's prediction
        crawlFeatureData();
        
        // Crawl historic data
        for(int w = weeks.size()-1; w >= 21; w--) {
            System.out.println("Processing week " + w);
            crawlTrainingData(weeks.get(w));
        }
        
    }
    
    /**
     * Crawls new feature data for next week's prediction
     */
    public void crawlFeatureData() {
        for(Metro m : metros) {
            try {
                System.out.println("Crawling new feature data for metro " + m.getName());
                Chart c = getCachedChart(m, weeks.get(weeks.size()-1));
                if(c != null) {
                    
                    // Find features
                    ArrayList<Feature> features = new ArrayList<Feature>();
                    for(Song s : c.getSongs()) {
                        features.add(getFeature(s, weeks.size()-1, m));
                    }
                    
                    // Create file and write
                    File file = new File("data/" + m.getName() + ".data.csv");
                    if(file.exists()) {
                        file.delete();
                    }
                    file.createNewFile();
                    FileWriter fw = new FileWriter(file);
                    for(Feature f : features) {
                        fw.write(f.toString() + "\n");
                    }
                    fw.close();
                    
                }
            } catch (IOException ex) {
            }
        }
    }
    
    /**
     * Crawls all possible training data for given week
     * 
     * @param week The week
     * @throws IOException
     * @throws ParseException
     * @throws JsonException 
     */
    public void crawlTrainingData(Week week) throws IOException {
        for(Metro m : metros) {
            System.out.println("  Processing metro " + m.getName());
            FeatureMatrix fm = new FeatureMatrix(m);
            fm.loadFromFile();
            if(!fm.containsWeek(weeks.get(week.getIndex()-1))) {
                crawlTrainingData(fm, m, week);
            }
        }
    }
    
    /**
     * Crawls all possible training data for given metro in given week
     * using the feature matrix to store the results
     * 
     * @param fm The feature matrix
     * @param metro The metro
     * @param week The week
     * @throws IOException
     * @throws ParseException
     * @throws JsonException 
     */
    public void crawlTrainingData(FeatureMatrix fm, Metro metro, Week week) throws IOException {
        Chart c = getCachedChart(metro, week);
        if(c != null) {
            for(Song s : c.getSongs()) {
                if(s != null) {
                    Feature f = getFeature(s, week.getIndex()-1, metro);
                    f.setOutput(""+getRank(s, week, metro));
                    fm.addFeature(f);
                }
            }
        }
        fm.saveToFile();
    }
    
    /**
     * Gets the cached chart for given metro and week
     * 
     * @param metro The metro
     * @param week The week
     * @return The chart as a traversable json object
     */
    public Chart getCachedChart(Metro metro, Week week) {
        
        // Attempt to get a cached copy of the chart
        Chart chart = chartCache[metro.getIndex()][week.getIndex()];
        
        if(chart == null) {
            
            // There is no copy in the cache, retrieve it manually
            chart = getChart(metro, week);
            
            // No chart exists, create an empty chart
            if(chart == null) {
                chart = new Chart();
            }
            
            // Store it in the cache for future reference
            chartCache[metro.getIndex()][week.getIndex()] = chart;
        }
        
        return chart;
    }
    
    /**
     * Gets the chart manually
     * 
     * @param metro The metro
     * @param week The week
     * @return The chart
     */
    public Chart getChart(Metro metro, Week week) {
        
        // The chart
        Chart chart = null;
        
        // Retry up to 5 times to get the chart if there is some error
        int retrycount = 0;
        while(retrycount < 5 && chart == null) {
            try {
                
                // Get the page from the last.fm API
                JsonCrawler jc = HttpCrawler.getJson("http://ws.audioscrobbler.com/2.0/?method=geo.getmetrotrackchart&start=" + URLEncoder.encode(week.getStart()+"", "UTF-8") + "&end=" + URLEncoder.encode(week.getEnd()+"", "UTF-8") + "&country=" + URLEncoder.encode(metro.getCountry(), "UTF-8") + "&metro=" + URLEncoder.encode(metro.getName(), "UTF-8") + "&api_key=dcd351ddc924b09be225a82db043311c&format=json");
                
                // Create an empty chart
                Chart c = new Chart();
                
                // Loop over all songs in the retrieved json and insert them in
                // the chart
                for(JsonCrawler j : jc.get("toptracks").get("track").list()) {
                    Song s = new Song(j.get("mbid").string(), j.get("name").string(), j.get("artist").get("name").string());
                    c.setSong(s, j.get("@attr").get("rank").integer());
                }
                
                // Store the chart so it can be returned
                chart = c;
                
            } catch(IOException ex) {
                
                // A connection error occurred, retry it
                System.out.println("        Error reading chart, retrying (attempt " + (retrycount+1) + " out of 5)");
                retrycount++;
                
                // Wait a while in case the server simply got overloaded
                try {
                    Thread.sleep(1000);
                } catch (InterruptedException ex1) {
                }
                
            } catch(Exception ex) {
                retrycount = 5;
                break;
            }
        }
        
        // Return the chart
        return chart;
        
    }
    
    /**
     * Gets the feature vector for given song in given week of given metro
     * 
     * @param song The song
     * @param weekIndex The week
     * @param metro The metro
     * @return The feature
     */
    public Feature getFeature(Song song, int weekIndex, Metro metro) {
        
        // Create the ranks string
        String ranks = "";
        
        // Loop over all metros for just this week
        for(Metro m : metros) {
            Week week = weeks.get(weekIndex);
            ranks += "," + getRank(song, week, m);
        }
        
        // Loop over all 20 historic weeks for this metro
        for(int w = weekIndex - 20; w < weekIndex; w++) {
            Week week = weeks.get(w);
            ranks += "," + getRank(song, week, metro);
        }
        
        // Create feature (removing the initial ",")
        return new Feature(weeks.get(weekIndex).getStart(), ranks.substring(1));
        
    }
    
    /**
     * Gets the rank of a song
     * 
     * @param song The song
     * @param week The week
     * @param metro The metro
     * @return The rank
     * @throws IOException
     * @throws ParseException
     * @throws JsonException 
     */
    public int getRank(Song song, Week week, Metro metro) {
        
        // Get rank data from cache if available
        Chart c = getCachedChart(metro, week);

        // Get the song's rank
        int rank = 0;
        if(song != null && c != null) {
            rank = c.getRank(song);
        }
        
        // Return it
        return rank;
        
    }
    
}
