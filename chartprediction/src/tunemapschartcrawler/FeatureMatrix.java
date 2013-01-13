package tunemapschartcrawler;

import java.io.File;
import java.io.FileNotFoundException;
import java.io.FileReader;
import java.io.FileWriter;
import java.io.IOException;
import java.util.LinkedList;
import java.util.Scanner;
import java.util.TreeSet;

/**
 * A feature matrix
 * 
 * @author Rolf Jagerman <rolf.jagerman@contended.nl>
 */
public class FeatureMatrix {
    
    /**
     * The metro
     */
    protected Metro metro;
    
    /**
     * The list of features
     */
    private LinkedList<Feature> features;
    
    /**
     * The list of weeks
     */
    private TreeSet<Integer> weeks;
    
    /**
     * Creates a new feature matrix for given metro
     * @param metro 
     */
    public FeatureMatrix(Metro metro) {
        this.metro = metro;
        features = new LinkedList<Feature>();
        weeks = new TreeSet<Integer>();
    }
    
    /**
     * Loads the feature matrix from a csv file
     * 
     * @throws FileNotFoundException 
     */
    public void loadFromFile() {
        try {
            File file = new File("data/" + metro.getName() + ".train.csv");
            Scanner sc = new Scanner(new FileReader(file));
            while(sc.hasNext()) {
                addFeature(new Feature(sc.nextLine(), true));
            }
            sc.close();
        } catch(FileNotFoundException e) {
        }
    }
    
    /**
     * Saves the feature matrix to a csv file
     * 
     * @throws IOException 
     */
    public void saveToFile() throws IOException {
        File file = new File("data/" + metro.getName() + ".train.csv");
        FileWriter fw = new FileWriter(file);
        for(Feature f : getFeatures()) {
            fw.write(f.toString());
            fw.write("\n");
        }
        fw.close();
    }
    
    /**
     * Returns true if the feature matrix contains given week
     * 
     * @param week The week
     * @return True if the feature matrix contains that week, false otherwise
     */
    public boolean containsWeek(Week week) {
        return weeks.contains(week.getStart());
    }
    
    /**
     * Adds a feature to the feature matrix
     * 
     * @param feature The feature
     */
    public void addFeature(Feature feature) {
        weeks.add(feature.getWeek());
        features.add(feature);
    }

    /**
     * @return the features
     */
    public LinkedList<Feature> getFeatures() {
        return features;
    }
    
}
