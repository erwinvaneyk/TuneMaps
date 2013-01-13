package tunemapschartcrawler;

/**
 * A metro
 * 
 * @author Rolf Jagerman <rolf.jagerman@contended.nl>
 */
public class Metro {
    
    /**
     * The index
     */
    private int index;
    
    /**
     * The country
     */
    private String country;
    
    /**
     * The name
     */
    private String name;
    
    /**
     * Creates a metro object
     * @param country the country
     * @param name the name
     */
    public Metro(String country, String name) {
        this.country = country;
        this.name = name;
    }

    /**
     * @return the country
     */
    public String getCountry() {
        return country;
    }

    /**
     * @param country the country to set
     */
    public void setCountry(String country) {
        this.country = country;
    }

    /**
     * @return the name
     */
    public String getName() {
        return name;
    }

    /**
     * @param name the name to set
     */
    public void setName(String name) {
        this.name = name;
    }

    /**
     * @return the index
     */
    public int getIndex() {
        return index;
    }

    /**
     * @param index the index to set
     */
    public void setIndex(int index) {
        this.index = index;
    }
    
}
