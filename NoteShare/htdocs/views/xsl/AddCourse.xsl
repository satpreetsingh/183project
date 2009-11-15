<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template match="/">
	<xsl:apply-templates/>
</xsl:template>

 <!--Specific format for populating the course combo box with the XML
      reponse from the controller. -->
<xsl:template match="universityList">
  <option value="-1"></option>
  <option value="0">Add New University...</option>
	<xsl:for-each select="University">
		<option>
			<xsl:attribute name="value">
				<xsl:value-of select="@Id" />
			</xsl:attribute>
			<xsl:value-of select="." />
		</option>
	</xsl:for-each>
</xsl:template>

 <!--Specific format for populating the course combo box with the XML
      reponse from the controller. -->
<xsl:template match="courseList">
	<xsl:for-each select="Course">
		<option>
			<xsl:attribute name="value">
				<xsl:value-of select="@Id" />
			</xsl:attribute>
			<xsl:value-of select="." />
		</option>
	</xsl:for-each>
</xsl:template>

<!--Specific format for populating the department combo box with the XML
      reponse from the controller. -->
<xsl:template match="deptList">
	<xsl:for-each select="Dept">
		<option>
			<xsl:attribute name="value">
				<xsl:value-of select="@Id" />
			</xsl:attribute>
			<xsl:value-of select="." />
		</option>
	</xsl:for-each>
</xsl:template>

<!--Specific format for populating the session combo box with the XML
      reponse from the controller. -->
<xsl:template match="sessionList">
	<xsl:for-each select="Session">
		<option>
			<xsl:attribute name="value">
				<xsl:value-of select="@Id" />
			</xsl:attribute>
			<xsl:value-of select="." />
		</option>
	</xsl:for-each>
</xsl:template>
  
</xsl:stylesheet>
